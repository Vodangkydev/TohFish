<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Image;
use App\Models\ParentModel;
use App\Models\Cv;
use App\Models\JobModel;
use App\Models\JobPosition;
use App\Models\Slider;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\PostService;
use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    protected $postService;
    protected $imageService;

    public function __construct(PostService $postService, ImageService $imageService)
    {
        $this->postService = $postService;
        $this->imageService = $imageService;
    }

    /**
     * Trang chủ admin
     */
    public function index()
    {
        $postsCount = Post::count();
        $imagesCount = Image::count();
        $latestPosts = Post::orderBy('created_at', 'desc')->take(5)->get();
        $latestImages = Image::orderBy('created_at', 'desc')->take(5)->get();

        // Xử lý URL hình ảnh theo MVC (logic ở Service)
        $latestImages->transform(function ($image) {
            $imageInfo = $this->imageService->getImageUrl($image);
            $image->display_url = $imageInfo['url'];
            $image->image_exists = $imageInfo['exists'];
            return $image;
        });

        return view('admin.index', compact('postsCount', 'imagesCount', 'latestPosts', 'latestImages'));
    }

    // ==================== POSTS MANAGEMENT ====================

    /**
     * Danh sách bài post
     */
    public function postsIndex(Request $request)
    {
        $query = Post::with(['roleCategory', 'positionCategory', 'locationCategory']);

        // Tìm kiếm
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('content', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $posts = $query->orderBy('created_at', 'desc')->paginate(15);
        
        return view('admin.posts.index', compact('posts'));
    }

    /**
     * Form tạo bài post
     */
    public function postsCreate()
    {
        $parents = ParentModel::all();
        return view('admin.posts.create', compact('parents'));
    }

    /**
     * Lưu bài post mới
     */
    public function postsStore(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'description' => 'nullable|string',
            'detail_content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'blog_type' => 'nullable|string|in:congthuc,monngon,tanman,farm,tour',
            'role' => 'boolean',
            'role_parent_id' => 'nullable|exists:parents,parent_id',
            'position_parent_id' => 'nullable|exists:parents,parent_id',
            'location_parent_id' => 'nullable|exists:parents,parent_id',
        ]);

        try {
            $data = $request->only([
                'content', 'description', 'role', 'blog_type',
                'role_parent_id', 'position_parent_id', 'location_parent_id'
            ]);
            
            // Đảm bảo description là null nếu rỗng
            if (empty($data['description'])) {
                $data['description'] = null;
            }

            // Upload ảnh chính nếu có
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('posts', 'public');
                $data['image_url'] = $imagePath;
            }

            // Upload ảnh phụ (tối đa 3 ảnh)
            $subImages = [];
            if ($request->hasFile('sub_images')) {
                foreach ($request->file('sub_images') as $subImage) {
                    if ($subImage && $subImage->isValid()) {
                        $subImagePath = $subImage->store('posts', 'public');
                        if ($subImagePath) {
                            $subImages[] = $subImagePath;
                            Log::info('Post sub image uploaded successfully: ' . $subImagePath);
                        }
                    }
                }
            }
            if (!empty($subImages)) {
                $data['sub_images'] = $subImages;
            }

            $data['view'] = 0;
            $data['status'] = $request->has('status') ? 1 : 0;
            
            // Đảm bảo role có giá trị mặc định
            if (!isset($data['role'])) {
                $data['role'] = 0;
            }
            
            // Đảm bảo image_url có giá trị nếu không upload
            if (!isset($data['image_url'])) {
                $data['image_url'] = null;
            }

            $post = Post::create($data);

            // Tạo hoặc cập nhật PostDetail nếu có nội dung chi tiết
            if ($request->has('detail_content') && !empty($request->detail_content)) {
                \App\Models\PostDetail::updateOrCreate(
                    ['post_id' => $post->post_id],
                    [
                        'content' => $request->detail_content,
                        'post_url' => route('chi-tiet-bai-viet', $post->post_id)
                    ]
                );
            }

            return redirect()->route('admin.posts.index')
                ->with('success', 'Bài post đã được tạo thành công!');
        } catch (\Exception $e) {
            Log::error('Error creating post: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo bài post.');
        }
    }

    /**
     * Form sửa bài post
     */
    public function postsEdit($id)
    {
        $post = Post::findOrFail($id);
        $post->load('postDetail');
        $parents = ParentModel::all();
        return view('admin.posts.edit', compact('post', 'parents'));
    }

    /**
     * Cập nhật bài post
     */
    public function postsUpdate(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'description' => 'nullable|string',
            'detail_content' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sub_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'blog_type' => 'nullable|string|in:congthuc,monngon,tanman,farm,tour',
            'role' => 'boolean',
            'role_parent_id' => 'nullable|exists:parents,parent_id',
            'position_parent_id' => 'nullable|exists:parents,parent_id',
            'location_parent_id' => 'nullable|exists:parents,parent_id',
        ]);

        try {
            $post = Post::findOrFail($id);
            $data = $request->only([
                'content', 'description', 'role', 'blog_type',
                'role_parent_id', 'position_parent_id', 'location_parent_id'
            ]);
            
            // Đảm bảo description là null nếu rỗng
            if (empty($data['description'])) {
                $data['description'] = null;
            }

            // Xử lý upload ảnh chính mới
            if ($request->hasFile('image')) {
                // Xóa ảnh cũ nếu có
                if ($post->image_url) {
                    Storage::disk('public')->delete($post->image_url);
                }
                $imagePath = $request->file('image')->store('posts', 'public');
                $data['image_url'] = $imagePath;
            }

            // Xử lý upload ảnh phụ
            if ($request->hasFile('sub_images')) {
                $subImages = $post->sub_images ?? [];
                $uploadedSubImages = [];
                
                foreach ($request->file('sub_images') as $index => $subImage) {
                    if ($subImage && $subImage->isValid()) {
                        // Nếu đã có ảnh phụ ở vị trí này, xóa ảnh cũ
                        if (isset($subImages[$index]) && $subImages[$index]) {
                            $oldSubPath = ltrim($subImages[$index], '/');
                            if (Storage::disk('public')->exists($oldSubPath)) {
                                Storage::disk('public')->delete($oldSubPath);
                            }
                        }
                        
                        // Upload ảnh phụ mới
                        $subImagePath = $subImage->store('posts', 'public');
                        if ($subImagePath) {
                            $uploadedSubImages[$index] = $subImagePath;
                            Log::info('Post sub image uploaded successfully: ' . $subImagePath);
                        }
                    } elseif (isset($subImages[$index])) {
                        // Giữ nguyên ảnh phụ cũ nếu không upload ảnh mới
                        $uploadedSubImages[$index] = $subImages[$index];
                    }
                }
                
                // Cập nhật mảng ảnh phụ, loại bỏ các giá trị null
                $uploadedSubImages = array_filter($uploadedSubImages, function($value) {
                    return $value !== null;
                });
                $uploadedSubImages = array_values($uploadedSubImages); // Reset keys
                
                // Nếu có ảnh phụ cũ không được thay thế, giữ lại
                if (count($subImages) > count($uploadedSubImages)) {
                    for ($i = count($uploadedSubImages); $i < count($subImages); $i++) {
                        if (isset($subImages[$i]) && $subImages[$i]) {
                            $uploadedSubImages[] = $subImages[$i];
                        }
                    }
                }
                
                $data['sub_images'] = !empty($uploadedSubImages) ? $uploadedSubImages : null;
            }

            $data['status'] = $request->has('status') ? 1 : 0;

            $post->update($data);

            // Cập nhật hoặc tạo PostDetail nếu có nội dung chi tiết
            if ($request->has('detail_content')) {
                \App\Models\PostDetail::updateOrCreate(
                    ['post_id' => $post->post_id],
                    [
                        'content' => $request->detail_content ?: '',
                        'post_url' => route('chi-tiet-bai-viet', $post->post_id)
                    ]
                );
            }

            return redirect()->route('admin.posts.index')
                ->with('success', 'Bài post đã được cập nhật thành công!');
        } catch (\Exception $e) {
            Log::error('Error updating post: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật bài post.');
        }
    }

    /**
     * Upload ảnh từ CKEditor
     */
    public function postsImageUpload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $file = $request->file('upload');
            
            // Validate
            $request->validate([
                'upload' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
            ]);
            
            $imagePath = $file->store('posts/editor', 'public');
            $url = route('storage.serve', ['path' => $imagePath]);
            
            return response()->json([
                'uploaded' => true,
                'url' => $url
            ]);
        }
        
        return response()->json([
            'uploaded' => false,
            'error' => ['message' => 'Không có file được upload']
        ], 400);
    }

    /**
     * Xóa bài post
     */
    public function postsDestroy($id)
    {
        try {
            $post = Post::findOrFail($id);
            
            // Xóa ảnh nếu có
            if ($post->image_url) {
                Storage::disk('public')->delete($post->image_url);
            }

            $post->delete();

            return redirect()->route('admin.posts.index')
                ->with('success', 'Bài post đã được xóa thành công!');
        } catch (\Exception $e) {
            Log::error('Error deleting post: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa bài post.');
        }
    }

    // ==================== PRODUCTS (IMAGES) MANAGEMENT ====================

    /**
     * Danh sách sản phẩm (Images)
     */
    public function productsIndex(Request $request)
    {
        $query = Image::query();

        // Tìm kiếm
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('content', 'LIKE', "%{$search}%")
                  ->orWhere('product_type', 'LIKE', "%{$search}%");
            });
        }

        $products = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Xử lý URL hình ảnh theo MVC (logic ở Service)
        $products->getCollection()->transform(function ($product) {
            $imageInfo = $this->imageService->getImageUrl($product);
            $product->display_url = $imageInfo['url'];
            $product->image_exists = $imageInfo['exists'];
            $product->raw_image_path = $imageInfo['raw_path'];
            return $product;
        });
        
        return view('admin.products.index', compact('products'));
    }

    /**
     * Form tạo sản phẩm
     */
    public function productsCreate()
    {
        return view('admin.products.create');
    }

    /**
     * Lưu sản phẩm mới
     */
    public function productsStore(Request $request)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sub_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'nullable|numeric|min:0',
            'size' => 'nullable|string|max:50',
            'product_type' => 'nullable|string|max:100',
            'discount_percent' => 'nullable|integer|min:0|max:100',
        ]);

        try {
            // Upload ảnh
            if (!$request->hasFile('image')) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Vui lòng chọn hình ảnh.');
            }

            // Kiểm tra thư mục storage
            $storagePath = storage_path('app/public/products');
            if (!file_exists($storagePath)) {
                File::makeDirectory($storagePath, 0755, true);
            }

            // Upload ảnh chính
            $imagePath = $request->file('image')->store('products', 'public');
            if (!$imagePath) {
                throw new \Exception('Không thể lưu file ảnh chính');
            }
            
            Log::info('Main image uploaded successfully: ' . $imagePath);

            // Upload ảnh phụ (tối đa 3 ảnh)
            $subImages = [];
            if ($request->hasFile('sub_images')) {
                foreach ($request->file('sub_images') as $subImage) {
                    if ($subImage && $subImage->isValid()) {
                        $subImagePath = $subImage->store('products', 'public');
                        if ($subImagePath) {
                            $subImages[] = $subImagePath;
                            Log::info('Sub image uploaded successfully: ' . $subImagePath);
                        }
                    }
                }
            }

            $data = [
                'content' => $request->content,
                'description' => $request->description ?: null,
                'image_url' => $imagePath,
                'size' => $request->size ?: null,
                'sub_images' => !empty($subImages) ? $subImages : null,
            ];

            // Chỉ thêm price, product_type, discount_percent nếu chúng tồn tại trong fillable
            if (in_array('price', (new Image())->getFillable())) {
                $data['price'] = $request->price ?: null;
            }
            if (in_array('product_type', (new Image())->getFillable())) {
                $data['product_type'] = $request->product_type ?: null;
            }
            if (in_array('discount_percent', (new Image())->getFillable())) {
                $data['discount_percent'] = $request->discount_percent ?: null;
            }

            $product = Image::create($data);
            Log::info('Product created successfully with ID: ' . $product->images_id);

            return redirect()->route('admin.products.index')
                ->with('success', 'Sản phẩm đã được tạo thành công!');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error creating product: ' . $e->getMessage());
            // Kiểm tra nếu là lỗi cột không tồn tại
            if (str_contains($e->getMessage(), "Unknown column 'price'") || str_contains($e->getMessage(), "Unknown column 'product_type'")) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Lỗi: Các cột price và product_type chưa được tạo trong database. Vui lòng chạy migration: php artisan migrate');
            }
            return redirect()->back()
                ->withInput()
                ->with('error', 'Lỗi database: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo sản phẩm: ' . $e->getMessage());
        }
    }

    /**
     * Form sửa sản phẩm
     */
    public function productsEdit($id)
    {
        $product = Image::findOrFail($id);
        
        // Xử lý URL hình ảnh theo MVC (logic ở Service)
        $imageInfo = $this->imageService->getImageUrl($product);
        $product->display_url = $imageInfo['url'];
        $product->image_exists = $imageInfo['exists'];
        $product->raw_image_path = $imageInfo['raw_path'];
        
        return view('admin.products.edit', compact('product'));
    }

    /**
     * Cập nhật sản phẩm
     */
    public function productsUpdate(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string|max:255',
            'description' => 'nullable|string|max:5000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'sub_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'nullable|numeric|min:0',
            'size' => 'nullable|string|max:50',
            'product_type' => 'nullable|string|max:100',
            'discount_percent' => 'nullable|integer|min:0|max:100',
        ]);

        try {
            $product = Image::findOrFail($id);
            
            // Kiểm tra thư mục storage
            $storagePath = storage_path('app/public/products');
            if (!file_exists($storagePath)) {
                File::makeDirectory($storagePath, 0755, true);
            }
            
            $data = [
                'content' => $request->content,
                'description' => $request->description ?: null,
                'price' => $request->price ?: null,
                'size' => $request->size ?: null,
                'product_type' => $request->product_type ?: null,
                'discount_percent' => $request->discount_percent !== null ? $request->discount_percent : $product->discount_percent,
            ];

            // Xử lý upload ảnh mới
            if ($request->hasFile('image')) {
                try {
                    // Xóa ảnh cũ nếu có
                    if ($product->image_url) {
                        $oldPath = ltrim($product->image_url, '/');
                        if (Storage::disk('public')->exists($oldPath)) {
                            Storage::disk('public')->delete($oldPath);
                        }
                    }
                    
                    // Upload ảnh mới
                    $imagePath = $request->file('image')->store('products', 'public');
                    if (!$imagePath) {
                        throw new \Exception('Không thể lưu file ảnh');
                    }
                    $data['image_url'] = $imagePath;
                    
                    Log::info('Main image uploaded successfully: ' . $imagePath);
                } catch (\Exception $e) {
                    Log::error('Error uploading main image: ' . $e->getMessage());
                    return redirect()->back()
                        ->withInput()
                        ->with('error', 'Lỗi khi upload ảnh chính: ' . $e->getMessage());
                }
            }

            // Xử lý upload ảnh phụ
            if ($request->hasFile('sub_images')) {
                $subImages = $product->sub_images ?? [];
                $uploadedSubImages = [];
                
                foreach ($request->file('sub_images') as $index => $subImage) {
                    if ($subImage && $subImage->isValid()) {
                        // Nếu đã có ảnh phụ ở vị trí này, xóa ảnh cũ
                        if (isset($subImages[$index]) && $subImages[$index]) {
                            $oldSubPath = ltrim($subImages[$index], '/');
                            if (Storage::disk('public')->exists($oldSubPath)) {
                                Storage::disk('public')->delete($oldSubPath);
                            }
                        }
                        
                        // Upload ảnh phụ mới
                        $subImagePath = $subImage->store('products', 'public');
                        if ($subImagePath) {
                            $uploadedSubImages[$index] = $subImagePath;
                            Log::info('Sub image uploaded successfully: ' . $subImagePath);
                        }
                    } elseif (isset($subImages[$index])) {
                        // Giữ nguyên ảnh phụ cũ nếu không upload ảnh mới
                        $uploadedSubImages[$index] = $subImages[$index];
                    }
                }
                
                // Cập nhật mảng ảnh phụ, loại bỏ các giá trị null
                $uploadedSubImages = array_filter($uploadedSubImages, function($value) {
                    return $value !== null;
                });
                $uploadedSubImages = array_values($uploadedSubImages); // Reset keys
                
                // Nếu có ảnh phụ cũ không được thay thế, giữ lại
                if (count($subImages) > count($uploadedSubImages)) {
                    for ($i = count($uploadedSubImages); $i < count($subImages); $i++) {
                        if (isset($subImages[$i]) && $subImages[$i]) {
                            $uploadedSubImages[] = $subImages[$i];
                        }
                    }
                }
                
                $data['sub_images'] = !empty($uploadedSubImages) ? $uploadedSubImages : null;
            }

            $product->update($data);
            
            Log::info('Product updated successfully: ' . $id);

            return redirect()->route('admin.products.index')
                ->with('success', 'Sản phẩm đã được cập nhật thành công!');
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error updating product: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Lỗi database: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật sản phẩm: ' . $e->getMessage());
        }
    }

    /**
     * Xóa sản phẩm
     */
    public function productsDestroy($id)
    {
        try {
            $product = Image::findOrFail($id);
            
            // Xóa ảnh nếu có
            if ($product->image_url) {
                Storage::disk('public')->delete($product->image_url);
            }

            $product->delete();

            return redirect()->route('admin.products.index')
                ->with('success', 'Sản phẩm đã được xóa thành công!');
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa sản phẩm.');
        }
    }

    // ==================== CVs MANAGEMENT ====================

    /**
     * Danh sách CV đã gửi
     */
    public function cvsIndex(Request $request)
    {
        $query = Cv::with('job');

        // Tìm kiếm
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ho_ten', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhere('phone', 'LIKE', "%{$search}%");
            });
        }

        // Lọc theo job_id
        if ($request->has('job_id') && $request->job_id) {
            $query->where('job_id', $request->job_id);
        }

        $cvs = $query->orderBy('created_at', 'desc')->paginate(15);
        $jobs = JobModel::all();

        return view('admin.cvs.index', compact('cvs', 'jobs'));
    }

    /**
     * Xem chi tiết CV
     */
    public function cvsShow($id)
    {
        $cv = Cv::with('job')->findOrFail($id);
        return view('admin.cvs.show', compact('cv'));
    }

    /**
     * Xem CV trực tiếp trong trình duyệt
     */
    public function cvsView($id)
    {
        try {
            $cv = Cv::findOrFail($id);
            
            if (!$cv->file_path) {
                return redirect()->back()
                    ->with('error', 'CV không có file đính kèm.');
            }

            $filePath = public_path('cv_files/' . $cv->file_path);
            
            if (!file_exists($filePath)) {
                return redirect()->back()
                    ->with('error', 'File CV không tồn tại.');
            }

            // Xác định content type dựa trên extension
            $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
            $contentType = 'application/octet-stream';
            
            switch($extension) {
                case 'pdf':
                    $contentType = 'application/pdf';
                    break;
                case 'doc':
                    $contentType = 'application/msword';
                    break;
                case 'docx':
                    $contentType = 'application/vnd.openxmlformats-officedocument.wordprocessingml.document';
                    break;
            }

            return response()->file($filePath, [
                'Content-Type' => $contentType,
                'Content-Disposition' => 'inline; filename="' . $cv->file_path . '"'
            ]);
        } catch (\Exception $e) {
            Log::error('Error viewing CV: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xem CV.');
        }
    }

    /**
     * Tải xuống CV
     */
    public function cvsDownload($id)
    {
        try {
            $cv = Cv::findOrFail($id);
            
            if (!$cv->file_path) {
                return redirect()->back()
                    ->with('error', 'CV không có file đính kèm.');
            }

            $filePath = public_path('cv_files/' . $cv->file_path);
            
            if (!file_exists($filePath)) {
                return redirect()->back()
                    ->with('error', 'File CV không tồn tại.');
            }

            return response()->download($filePath, $cv->file_path);
        } catch (\Exception $e) {
            Log::error('Error downloading CV: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi tải xuống CV.');
        }
    }

    /**
     * Xóa CV
     */
    public function cvsDestroy($id)
    {
        try {
            $cv = Cv::findOrFail($id);
            
            // Xóa file CV nếu tồn tại
            if ($cv->file_path && file_exists(public_path('cv_files/' . $cv->file_path))) {
                unlink(public_path('cv_files/' . $cv->file_path));
            }

            $cv->delete();

            return redirect()->route('admin.cvs.index')
                ->with('success', 'CV đã được xóa thành công!');
        } catch (\Exception $e) {
            Log::error('Error deleting CV: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa CV.');
        }
    }

    // ==================== JOB POSITIONS MANAGEMENT ====================

    /**
     * Danh sách chức vụ
     */
    public function jobPositionsIndex(Request $request)
    {
        $query = JobPosition::query();

        // Tìm kiếm
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('content', 'LIKE', "%{$search}%");
            });
        }

        $positions = $query->orderBy('published_at', 'desc')
                          ->orderBy('created_at', 'desc')
                          ->paginate(15);

        return view('admin.job-positions.index', compact('positions'));
    }

    /**
     * Form tạo chức vụ mới
     */
    public function jobPositionsCreate()
    {
        return view('admin.job-positions.create');
    }

    /**
     * Lưu chức vụ mới
     */
    public function jobPositionsStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'published_at' => 'nullable|date',
        ]);

        try {
            JobPosition::create([
                'title' => $request->title,
                'content' => $request->content,
                'published_at' => $request->published_at ? date('Y-m-d H:i:s', strtotime($request->published_at)) : now(),
            ]);

            return redirect()->route('admin.job-positions.index')
                ->with('success', 'Chức vụ đã được tạo thành công!');
        } catch (\Exception $e) {
            Log::error('Error creating job position: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo chức vụ.');
        }
    }

    /**
     * Form chỉnh sửa chức vụ
     */
    public function jobPositionsEdit($id)
    {
        $position = JobPosition::findOrFail($id);
        return view('admin.job-positions.edit', compact('position'));
    }

    /**
     * Cập nhật chức vụ
     */
    public function jobPositionsUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'published_at' => 'nullable|date',
        ]);

        try {
            $position = JobPosition::findOrFail($id);
            $position->update([
                'title' => $request->title,
                'content' => $request->content,
                'published_at' => $request->published_at ? date('Y-m-d H:i:s', strtotime($request->published_at)) : $position->published_at,
            ]);

            return redirect()->route('admin.job-positions.index')
                ->with('success', 'Chức vụ đã được cập nhật thành công!');
        } catch (\Exception $e) {
            Log::error('Error updating job position: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật chức vụ.');
        }
    }

    /**
     * Xóa chức vụ
     */
    public function jobPositionsDestroy($id)
    {
        try {
            $position = JobPosition::findOrFail($id);
            $position->delete();

            return redirect()->route('admin.job-positions.index')
                ->with('success', 'Chức vụ đã được xóa thành công!');
        } catch (\Exception $e) {
            Log::error('Error deleting job position: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa chức vụ.');
        }
    }

    // ==================== SLIDERS MANAGEMENT ====================

    /**
     * Danh sách slider
     */
    public function slidersIndex(Request $request)
    {
        $query = Slider::query();

        // Lọc theo type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Tìm kiếm
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('description', 'LIKE', "%{$search}%");
            });
        }

        $sliders = $query->ordered()->paginate(15);

        return view('admin.sliders.index', compact('sliders'));
    }

    /**
     * Form tạo slider
     */
    public function slidersCreate()
    {
        return view('admin.sliders.create');
    }

    /**
     * Lưu slider mới
     */
    public function slidersStore(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'link_2' => 'nullable|string|max:255',
            'button_text_2' => 'nullable|string|max:255',
            'type' => 'required|in:home,promotion',
            'order' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
            'background_color' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:255',
        ]);

        try {
            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'link' => $request->link,
                'button_text' => $request->button_text,
                'link_2' => $request->link_2,
                'button_text_2' => $request->button_text_2,
                'type' => $request->type,
                'order' => $request->order ?? 0,
                'status' => $request->has('status') ? true : false,
                'background_color' => $request->background_color,
                'icon' => $request->icon,
            ];

            // Upload ảnh nếu có
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('sliders', $imageName, 'public');
                $data['image_url'] = $imagePath;
            }

            Slider::create($data);

            return redirect()->route('admin.sliders.index')
                ->with('success', 'Slider đã được tạo thành công!');
        } catch (\Exception $e) {
            Log::error('Error creating slider: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi tạo slider.');
        }
    }

    /**
     * Form chỉnh sửa slider
     */
    public function slidersEdit($id)
    {
        $slider = Slider::findOrFail($id);
        return view('admin.sliders.edit', compact('slider'));
    }

    /**
     * Cập nhật slider
     */
    public function slidersUpdate(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'link' => 'nullable|string|max:255',
            'button_text' => 'nullable|string|max:255',
            'link_2' => 'nullable|string|max:255',
            'button_text_2' => 'nullable|string|max:255',
            'type' => 'required|in:home,promotion',
            'order' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
            'background_color' => 'nullable|string|max:50',
            'icon' => 'nullable|string|max:255',
        ]);

        try {
            $slider = Slider::findOrFail($id);

            $data = [
                'title' => $request->title,
                'description' => $request->description,
                'link' => $request->link,
                'button_text' => $request->button_text,
                'link_2' => $request->link_2,
                'button_text_2' => $request->button_text_2,
                'type' => $request->type,
                'order' => $request->order ?? 0,
                'status' => $request->has('status') ? true : false,
                'background_color' => $request->background_color,
                'icon' => $request->icon,
            ];

            // Upload ảnh mới nếu có
            if ($request->hasFile('image')) {
                // Xóa ảnh cũ nếu có
                if ($slider->image_url) {
                    Storage::disk('public')->delete($slider->image_url);
                }

                $image = $request->file('image');
                $imageName = time() . '_' . Str::random(10) . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('sliders', $imageName, 'public');
                $data['image_url'] = $imagePath;
            }

            $slider->update($data);

            return redirect()->route('admin.sliders.index')
                ->with('success', 'Slider đã được cập nhật thành công!');
        } catch (\Exception $e) {
            Log::error('Error updating slider: ' . $e->getMessage());
            return redirect()->back()
                ->withInput()
                ->with('error', 'Có lỗi xảy ra khi cập nhật slider.');
        }
    }

    /**
     * Xóa slider
     */
    public function slidersDestroy($id)
    {
        try {
            $slider = Slider::findOrFail($id);

            // Xóa ảnh nếu có
            if ($slider->image_url) {
                Storage::disk('public')->delete($slider->image_url);
            }

            $slider->delete();

            return redirect()->route('admin.sliders.index')
                ->with('success', 'Slider đã được xóa thành công!');
        } catch (\Exception $e) {
            Log::error('Error deleting slider: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xóa slider.');
        }
    }

    // ==================== ORDERS MANAGEMENT ====================

    /**
     * Danh sách đơn hàng
     */
    public function ordersIndex(Request $request)
    {
        $query = Order::with(['user', 'items']);

        // Tìm kiếm
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('code', 'LIKE', "%{$search}%")
                  ->orWhere('shipping_name', 'LIKE', "%{$search}%")
                  ->orWhere('shipping_phone', 'LIKE', "%{$search}%")
                  ->orWhere('email', 'LIKE', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%");
                  });
            });
        }

        // Lọc theo trạng thái
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Lọc theo phương thức thanh toán
        if ($request->has('payment_method') && $request->payment_method) {
            $query->where('payment_method', $request->payment_method);
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        // Thống kê
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
        ];

        return view('admin.orders.index', compact('orders', 'stats'));
    }

    /**
     * Chi tiết đơn hàng
     */
    public function ordersShow($id)
    {
        $order = Order::with(['user', 'items.product'])
            ->findOrFail($id);
        
        $paymentTransaction = \App\Models\PaymentTransaction::where('order_id', $order->id)
            ->latest()
            ->first();

        return view('admin.orders.show', compact('order', 'paymentTransaction'));
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function ordersUpdateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,completed,cancelled',
        ]);

        try {
            $order = Order::findOrFail($id);
            $oldStatus = $order->status;
            $order->update([
                'status' => $request->status,
            ]);

            // Nếu order status được cập nhật thành 'completed' và có payment transaction, cập nhật payment status
            if ($request->status === 'completed') {
                $paymentTransaction = \App\Models\PaymentTransaction::where('order_id', $order->id)
                    ->where('status', 'pending')
                    ->latest()
                    ->first();
                
                if ($paymentTransaction) {
                    $paymentTransaction->update([
                        'status' => 'completed',
                        'paid_at' => now(),
                    ]);
                    
                    Log::info('Payment transaction auto-completed when order status updated', [
                        'order_id' => $order->id,
                        'payment_transaction_id' => $paymentTransaction->id,
                    ]);
                }
            }

            $statusLabels = [
                'pending' => 'Chờ xử lý',
                'processing' => 'Đang xử lý',
                'completed' => 'Hoàn thành',
                'cancelled' => 'Đã hủy',
            ];

            Log::info('Order status updated', [
                'order_id' => $order->id,
                'order_code' => $order->code,
                'old_status' => $oldStatus,
                'new_status' => $request->status,
            ]);

            // Nếu request đến từ trang index, redirect về index, ngược lại về show
            $referer = $request->headers->get('referer');
            if ($referer && str_contains($referer, route('admin.orders.index'))) {
                return redirect()->route('admin.orders.index')
                    ->with('success', "Trạng thái đơn hàng {$order->code} đã được cập nhật thành '{$statusLabels[$request->status]}'.");
            }

            return redirect()->route('admin.orders.show', $id)
                ->with('success', "Trạng thái đơn hàng đã được cập nhật thành '{$statusLabels[$request->status]}'.");
        } catch (\Exception $e) {
            Log::error('Error updating order status: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi cập nhật trạng thái đơn hàng.');
        }
    }

    /**
     * Xác nhận thanh toán cho đơn hàng
     */
    public function ordersConfirmPayment($id)
    {
        try {
            $order = Order::findOrFail($id);
            
            $paymentTransaction = \App\Models\PaymentTransaction::where('order_id', $order->id)
                ->latest()
                ->first();
            
            if (!$paymentTransaction) {
                return redirect()->back()
                    ->with('error', 'Không tìm thấy giao dịch thanh toán cho đơn hàng này.');
            }

            if ($paymentTransaction->status === 'completed') {
                return redirect()->back()
                    ->with('info', 'Thanh toán đã được xác nhận trước đó.');
            }

            // Xác nhận thanh toán (cho phép xác nhận cả khi expired hoặc failed)
            $paymentTransaction->update([
                'status' => 'completed',
                'paid_at' => now(),
            ]);
            
            // Tự động cập nhật trạng thái đơn hàng sang processing nếu đang pending
            if ($order->status === 'pending') {
                $order->update([
                    'status' => 'processing'
                ]);
            }

            Log::info('Payment confirmed by admin', [
                'order_id' => $order->id,
                'order_code' => $order->code,
                'payment_transaction_id' => $paymentTransaction->id,
                'admin_id' => Auth::id(),
            ]);

            return redirect()->back()
                ->with('success', 'Đã xác nhận thanh toán thành công cho đơn hàng ' . $order->code . '.');
        } catch (\Exception $e) {
            Log::error('Error confirming payment: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Có lỗi xảy ra khi xác nhận thanh toán.');
        }
    }
}

