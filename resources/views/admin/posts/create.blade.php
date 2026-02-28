@extends('admin.layout')

@section('title', 'Thêm Bài Post Mới')

@section('content')
<div class="admin-header">
    <h2><i class="fas fa-plus"></i> Thêm Bài Post Mới</h2>
    <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

<div class="admin-card">
    <form action="{{ route('admin.posts.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="content" class="form-label">Nội dung <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('content') is-invalid @enderror" 
                           id="content" name="content" value="{{ old('content') }}" required>
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="detail_content" class="form-label">Nội dung chi tiết</label>
                    <textarea class="form-control @error('detail_content') is-invalid @enderror" 
                              id="detail_content" name="detail_content" rows="15">{{ old('detail_content') }}</textarea>
                    @error('detail_content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Có thể định dạng văn bản (in đậm, in nghiêng, thêm ảnh, v.v.)</small>
                </div>

                <div class="mb-3">
                    <label for="blog_type" class="form-label">Loại</label>
                    <select class="form-select @error('blog_type') is-invalid @enderror" id="blog_type" name="blog_type">
                        <option value="">Chọn...</option>
                        <option value="congthuc" {{ old('blog_type') == 'congthuc' ? 'selected' : '' }}>Công Thức Món Cá</option>
                        <option value="monngon" {{ old('blog_type') == 'monngon' ? 'selected' : '' }}>Công Thức Món Ngon</option>
                        <option value="tanman" {{ old('blog_type') == 'tanman' ? 'selected' : '' }}>Tản Mạn Cùng TOH fish</option>
                        <option value="farm" {{ old('blog_type') == 'farm' ? 'selected' : '' }}>TOH Farm - Nông Trại</option>
                        <option value="tour" {{ old('blog_type') == 'tour' ? 'selected' : '' }}>TOH Tour - Tham Quan</option>
                    </select>
                    @error('blog_type')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="role_parent_id" class="form-label">Role Category</label>
                            <select class="form-select" id="role_parent_id" name="role_parent_id">
                                <option value="">Chọn...</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->parent_id }}" {{ old('role_parent_id') == $parent->parent_id ? 'selected' : '' }}>
                                        {{ $parent->parent_name ?? $parent->parent_id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="position_parent_id" class="form-label">Position Category</label>
                            <select class="form-select" id="position_parent_id" name="position_parent_id">
                                <option value="">Chọn...</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->parent_id }}" {{ old('position_parent_id') == $parent->parent_id ? 'selected' : '' }}>
                                        {{ $parent->parent_name ?? $parent->parent_id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="location_parent_id" class="form-label">Location Category</label>
                            <select class="form-select" id="location_parent_id" name="location_parent_id">
                                <option value="">Chọn...</option>
                                @foreach($parents as $parent)
                                    <option value="{{ $parent->parent_id }}" {{ old('location_parent_id') == $parent->parent_id ? 'selected' : '' }}>
                                        {{ $parent->parent_name ?? $parent->parent_id }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label for="image" class="form-label">Hình ảnh chính</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                           id="image" name="image" accept="image/*" onchange="previewImage(this, 'main')">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Chấp nhận: JPG, PNG, GIF (tối đa 2MB)</small>
                    <div id="imagePreview" class="mt-3"></div>
                </div>

                <div class="mb-3">
                    <label class="form-label">Ảnh phụ (tối đa 3 ảnh)</label>
                    @for($i = 1; $i <= 3; $i++)
                        <div class="mb-2">
                            <input type="file" class="form-control @error('sub_images.' . ($i-1)) is-invalid @enderror" 
                                   id="sub_image_{{ $i }}" name="sub_images[]" accept="image/*" 
                                   onchange="previewSubImage(this, {{ $i }})">
                            @error('sub_images.' . ($i-1))
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div id="subImagePreview_{{ $i }}" class="mt-2"></div>
                        </div>
                    @endfor
                    <small class="form-text text-muted">Ảnh phụ sẽ hiển thị ở trang chi tiết bài viết</small>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="role" name="role" value="1" {{ old('role') ? 'checked' : '' }}>
                        <label class="form-check-label" for="role">Role</label>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status') ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">Hiển thị</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Lưu
            </button>
            <a href="{{ route('admin.posts.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Hủy
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.ckeditor.com/4.21.0/full/ckeditor.js"></script>
<script>
    CKEDITOR.replace('detail_content', {
        versionCheck: false,
        removePlugins: 'exportpdf,uploadimage',
        toolbar: [
            { name: 'styles', items: ['Format', 'Font', 'FontSize'] },
            { name: 'basicstyles', items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript'] },
            { name: 'colors', items: ['TextColor', 'BGColor'] },
            { name: 'paragraph', items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
            { name: 'links', items: ['Link', 'Unlink'] },
            { name: 'insert', items: ['Image', 'Table', 'HorizontalRule', 'SpecialChar'] },
            { name: 'tools', items: ['Maximize', 'Source'] }
        ],
        height: 400,
        filebrowserUploadUrl: '{{ route("admin.posts.image-upload") }}',
        filebrowserUploadMethod: 'form'
    });
    
    function previewImage(input, type) {
        const preview = document.getElementById('imagePreview');
        if (input.files && input.files[0]) {
            // Kiểm tra kích thước file (2MB = 2097152 bytes)
            if (input.files[0].size > 2097152) {
                alert('File quá lớn! Vui lòng chọn file nhỏ hơn 2MB.');
                input.value = '';
                preview.innerHTML = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="img-preview" style="max-width: 300px; max-height: 300px; border-radius: 8px;">`;
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.innerHTML = '';
        }
    }

    function previewSubImage(input, index) {
        const preview = document.getElementById('subImagePreview_' + index);
        if (input.files && input.files[0]) {
            // Kiểm tra kích thước file (2MB = 2097152 bytes)
            if (input.files[0].size > 2097152) {
                alert('File quá lớn! Vui lòng chọn file nhỏ hơn 2MB.');
                input.value = '';
                preview.innerHTML = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="img-preview" style="max-width: 150px; max-height: 150px; border-radius: 4px;">`;
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            preview.innerHTML = '';
        }
    }
</script>
@endpush
@endsection

