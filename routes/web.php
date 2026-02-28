<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\TohfishController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StorageController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Storage Route - Phải đặt trước các route khác để serve ảnh từ storage
// Route này sẽ serve ảnh trực tiếp từ storage/app/public mà không cần symlink
Route::get('/storage/{path}', [StorageController::class, 'serve'])
    ->where('path', '.*')
    ->name('storage.serve');

// Route để serve ảnh từ public/images
Route::get('/images/{path}', function ($path) {
    $fullPath = public_path('images/' . $path);
    
    if (!file_exists($fullPath) || !is_file($fullPath)) {
        abort(404);
    }
    
    $mimeType = mime_content_type($fullPath);
    $file = file_get_contents($fullPath);
    
    return response($file, 200)
        ->header('Content-Type', $mimeType)
        ->header('Content-Length', filesize($fullPath))
        ->header('Cache-Control', 'public, max-age=31536000');
})->where('path', '.*');

// TOH Fish Routes
Route::get('/', [TohfishController::class, 'index'])->name('home');
Route::get('/home', [TohfishController::class, 'index']);

// Products Routes
Route::get('/san-pham-moi-nhat', [TohfishController::class, 'productsLatest'])->name('products.latest');
Route::get('/san-pham-ban-chay', [TohfishController::class, 'productsBestSelling'])->name('products.best_selling');
Route::get('/san-pham-so-che', [TohfishController::class, 'productsSoche'])->name('products.soche');
Route::get('/san-pham-che-bien', [TohfishController::class, 'productsChebien'])->name('products.chebien');
Route::get('/san-pham-che-bien-san', [TohfishController::class, 'productsChebiensan'])->name('products.chebiensan');
Route::get('/san-pham-khac', [TohfishController::class, 'productsKhac'])->name('products.khac');
Route::get('/bun-ca-toh', [TohfishController::class, 'productsBunca'])->name('products.bunca');
Route::get('/rau-gia-vi', [TohfishController::class, 'productsRaugiavi'])->name('products.raugiavi');
Route::get('/san-pham/{id}', [TohfishController::class, 'productDetail'])->name('product.detail');
Route::get('/tim-kiem', [TohfishController::class, 'searchProducts'])->name('products.search');

// Favorites Routes
// Check route không cần auth vì controller đã xử lý
Route::get('/yeu-thich/check/{productId}', [FavoriteController::class, 'check'])->name('favorites.check');

Route::middleware('auth')->group(function () {
    Route::get('/yeu-thich/list', [FavoriteController::class, 'list'])->name('favorites.list');
    Route::post('/yeu-thich/{productId}', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
    Route::get('/yeu-thich', [FavoriteController::class, 'index'])->name('favorites.index');
});

// Information Routes
Route::get('/cach-dat-hang', [TohfishController::class, 'orderGuide'])->name('order-guide');
Route::get('/gioi-thieu', [TohfishController::class, 'about'])->name('about');
Route::get('/lien-he', [TohfishController::class, 'contact'])->name('contact');

// Blog Routes
Route::get('/blog', [TohfishController::class, 'blogIndex'])->name('blog.index');
Route::get('/cong-thuc-mon-ca', [TohfishController::class, 'blogCongthuc'])->name('blog.congthuc');
Route::get('/cong-thuc-mon-ngon', [TohfishController::class, 'blogMonngon'])->name('blog.monngon');
Route::get('/tan-man', [TohfishController::class, 'blogTanman'])->name('blog.tanman');
Route::get('/toh-farm', [TohfishController::class, 'blogFarm'])->name('blog.farm');
Route::get('/toh-tour', [TohfishController::class, 'blogTour'])->name('blog.tour');

// Other Routes
Route::get('/khuyen-mai', [TohfishController::class, 'promotion'])->name('promotion');
Route::get('/tuyen-dung-toh/chi-tiet/{id}', [TohfishController::class, 'jobPositionDetail'])->name('job-position.detail');
Route::get('/tuyen-dung-toh', [TohfishController::class, 'recruitment'])->name('recruitment');
Route::get('/chinh-sach', [TohfishController::class, 'policy'])->name('policy');
Route::get('/bao-mat', [TohfishController::class, 'privacy'])->name('privacy');

// Cart Routes - Đặt trước để tránh conflict
Route::get('/gio-hang', [CartController::class, 'index'])->name('cart');
Route::post('/gio-hang/them', [CartController::class, 'add'])->name('cart.add');
Route::post('/gio-hang/cap-nhat', [CartController::class, 'update'])->name('cart.update');
Route::post('/gio-hang/xoa', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/gio-hang/xoa-tat-ca', [CartController::class, 'clearAll'])->name('cart.clear_all');
Route::post('/mua-ngay', [CartController::class, 'buyNow'])->name('cart.buy_now');
Route::post('/gio-hang/cap-nhat-chon', [CartController::class, 'updateSelection'])->name('cart.update_selection');
Route::post('/gio-hang/cap-nhat-chon-nhieu', [CartController::class, 'updateSelections'])->name('cart.update_selections');

// Redirect để xử lý trường hợp URL bị lặp base path (Tohfish...)
// Tất cả các GET request với base path bị lặp sẽ được redirect về trang giỏ hàng
Route::get('/Tohfish/gio-hang', function() {
    return redirect()->route('cart', [], 301);
});
Route::get('/Tohfish/gio-hang/{any}', function($any) {
    // Redirect tất cả các sub-route về trang giỏ hàng
    // Vì các route cart (cap-nhat, xoa, etc.) là POST, không thể truy cập trực tiếp qua GET
    return redirect()->route('cart', [], 301);
})->where('any', '.*');
Route::get('/thanh-toan', [TohfishController::class, 'checkout'])->name('checkout');

// Order Routes
Route::middleware('auth')->group(function () {
    Route::post('/dat-hang', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/don-hang/thanh-cong/{id}', [OrderController::class, 'success'])->name('orders.success');
    Route::get('/lich-su-mua-hang', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/don-hang/{id}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/don-hang/{id}/huy', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/don-hang/{id}/mua-lai', [OrderController::class, 'reorder'])->name('orders.reorder');
    
    // Payment check route
    Route::get('/payment/check/{orderId}', [PaymentController::class, 'check'])->name('payment.check');
});

// Webhook để nhận callback từ VietQR/ngân hàng (không cần auth)
Route::post('/payment/webhook', [PaymentController::class, 'webhook'])->name('payment.webhook');

// MoMo Payment Routes
Route::middleware('auth')->group(function () {
    Route::post('/payment/momo/initiate', [PaymentController::class, 'momoInitiate'])->name('payment.momo.initiate');
    Route::post('/confirm_momo', [PaymentController::class, 'confirmMomo'])->name('payment.momo.confirm');
});
// MoMo IPN và Redirect (không cần auth vì được gọi từ MoMo)
Route::post('/payment/momo/ipn', [PaymentController::class, 'momoIpn'])->name('payment.momo.ipn');
Route::get('/payment/momo/redirect', [PaymentController::class, 'momoRedirect'])->name('payment.momo.redirect');
Route::get('/payment/momo/post', [PaymentController::class, 'momoPost'])->name('payment.momo.post');


Route::get('/bai-viet', [PostController::class, 'index'])->name('bai-viet');
Route::get('/chi-tiet-bai-viet/{id}', [ArticleController::class, 'show'])->name('chi-tiet-bai-viet');

Route::get('/tai-lieu', [DocumentController::class, 'index'])->name('tai-lieu');

Route::get('/tuyen-dung', [CategoryController::class, 'index'])->name('tuyen-dung');
Route::get('/search-categories', [CategoryController::class, 'searchCategories']);
Route::get('/chi-tiet-tuyen-dung/{id}', [JobController::class, 'show'])->name('chi-tiet-tuyen-dung');
Route::post('/upload/{id?}', [JobController::class, 'upload'])->name('upload');

// Auth routes
Route::middleware('guest')->group(function () {
    Route::view('/login', 'auth.login')->name('login');
    Route::view('/register', 'auth.register')->name('register');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');
});


Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// Profile
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile');

    Route::get('/profile/edit', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::post('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('index');
    
    // Posts Management
    Route::prefix('posts')->name('posts.')->group(function () {
        Route::get('/', [AdminController::class, 'postsIndex'])->name('index');
        Route::get('/create', [AdminController::class, 'postsCreate'])->name('create');
        Route::post('/', [AdminController::class, 'postsStore'])->name('store');
        Route::post('/image-upload', [AdminController::class, 'postsImageUpload'])->name('image-upload');
        Route::get('/{id}/edit', [AdminController::class, 'postsEdit'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'postsUpdate'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'postsDestroy'])->name('destroy');
    });
    
    // Products (Images) Management
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [AdminController::class, 'productsIndex'])->name('index');
        Route::get('/create', [AdminController::class, 'productsCreate'])->name('create');
        Route::post('/', [AdminController::class, 'productsStore'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'productsEdit'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'productsUpdate'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'productsDestroy'])->name('destroy');
    });
    
    // CVs Management
    Route::prefix('cvs')->name('cvs.')->group(function () {
        Route::get('/', [AdminController::class, 'cvsIndex'])->name('index');
        Route::get('/{id}/view', [AdminController::class, 'cvsView'])->name('view');
        Route::get('/{id}/download', [AdminController::class, 'cvsDownload'])->name('download');
        Route::get('/{id}', [AdminController::class, 'cvsShow'])->name('show');
        Route::delete('/{id}', [AdminController::class, 'cvsDestroy'])->name('destroy');
    });

    // Job Positions Management
    Route::prefix('job-positions')->name('job-positions.')->group(function () {
        Route::get('/', [AdminController::class, 'jobPositionsIndex'])->name('index');
        Route::get('/create', [AdminController::class, 'jobPositionsCreate'])->name('create');
        Route::post('/', [AdminController::class, 'jobPositionsStore'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'jobPositionsEdit'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'jobPositionsUpdate'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'jobPositionsDestroy'])->name('destroy');
    });

    // Sliders Management
    Route::prefix('sliders')->name('sliders.')->group(function () {
        Route::get('/', [AdminController::class, 'slidersIndex'])->name('index');
        Route::get('/create', [AdminController::class, 'slidersCreate'])->name('create');
        Route::post('/', [AdminController::class, 'slidersStore'])->name('store');
        Route::get('/{id}/edit', [AdminController::class, 'slidersEdit'])->name('edit');
        Route::put('/{id}', [AdminController::class, 'slidersUpdate'])->name('update');
        Route::delete('/{id}', [AdminController::class, 'slidersDestroy'])->name('destroy');
    });

    // Orders Management
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminController::class, 'ordersIndex'])->name('index');
        Route::get('/{id}', [AdminController::class, 'ordersShow'])->name('show');
        Route::put('/{id}/status', [AdminController::class, 'ordersUpdateStatus'])->name('update-status');
        Route::post('/{id}/confirm-payment', [AdminController::class, 'ordersConfirmPayment'])->name('confirm-payment');
    });
});