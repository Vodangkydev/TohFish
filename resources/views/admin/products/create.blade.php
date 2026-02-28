@extends('admin.layout')

@section('title', 'Thêm Sản Phẩm Mới')

@section('content')
<div class="admin-header">
    <h2><i class="fas fa-plus"></i> Thêm Sản Phẩm Mới</h2>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
        <i class="fas fa-arrow-left"></i> Quay lại
    </a>
</div>

@if($errors->any())
<div class="alert alert-danger">
    <h5><i class="fas fa-exclamation-triangle"></i> Có lỗi xảy ra:</h5>
    <ul class="mb-0">
        @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif

<div class="admin-card">
    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="content" class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('content') is-invalid @enderror" 
                           id="content" name="content" value="{{ old('content') }}" required placeholder="Nhập tên sản phẩm">
                    @error('content')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả sản phẩm</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="5" 
                              placeholder="Nhập mô tả chi tiết về sản phẩm...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Mô tả chi tiết về sản phẩm (tùy chọn)</small>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="product_type" class="form-label">Loại hàng</label>
                            <select class="form-select @error('product_type') is-invalid @enderror" 
                                    id="product_type" name="product_type">
                                <option value="">Chọn loại hàng...</option>
                                <option value="Sơ chế" {{ old('product_type') == 'Sơ chế' ? 'selected' : '' }}>Sơ chế</option>
                                <option value="Chế biến" {{ old('product_type') == 'Chế biến' ? 'selected' : '' }}>Chế biến</option>
                                <option value="Chế biến sẵn" {{ old('product_type') == 'Chế biến sẵn' ? 'selected' : '' }}>Chế biến sẵn</option>
                                <option value="Bún cá TOH" {{ old('product_type') == 'Bún cá TOH' ? 'selected' : '' }}>Bún cá TOH</option>
                                <option value="Rau gia vị" {{ old('product_type') == 'Rau gia vị' ? 'selected' : '' }}>Rau gia vị</option>
                                <option value="Khuyến mãi" {{ old('product_type') == 'Khuyến mãi' ? 'selected' : '' }}>Khuyến mãi</option>
                                <option value="Khác" {{ old('product_type') == 'Khác' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('product_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="price" class="form-label">Giá (VNĐ)</label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" 
                                   id="price" name="price" value="{{ old('price') }}" 
                                   placeholder="Nhập giá sản phẩm" min="0" step="1000">
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Ví dụ: 135000</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="discount_percent" class="form-label">Giảm giá (%)</label>
                            <div class="input-group">
                                <input type="number" class="form-control @error('discount_percent') is-invalid @enderror"
                                       id="discount_percent" name="discount_percent" value="{{ old('discount_percent') }}"
                                       placeholder="Nhập % giảm, ví dụ 10" min="0" max="100" step="1">
                                <span class="input-group-text">%</span>
                            </div>
                            @error('discount_percent')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Để trống nếu không khuyến mãi. Ví dụ: nhập 10 → giảm 10%.</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="size" class="form-label">Kích thước</label>
                            <input type="text" class="form-control @error('size') is-invalid @enderror" 
                                   id="size" name="size" value="{{ old('size') }}" 
                                   placeholder="Ví dụ: 300g/gói, 500g, 1kg">
                            @error('size')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Ví dụ: 300g/gói, 500g, 1kg</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label for="image" class="form-label">Hình ảnh chính <span class="text-danger">*</span></label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                           id="image" name="image" accept="image/*" required onchange="previewImage(this, 'main')">
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
                    <small class="form-text text-muted">Ảnh phụ sẽ hiển thị ở trang chi tiết sản phẩm</small>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Lưu
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Hủy
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
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
