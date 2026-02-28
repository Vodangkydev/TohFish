@extends('admin.layout')

@section('title', 'Sửa Slider')

@section('content')
<div class="admin-header">
    <h2><i class="fas fa-edit"></i> Sửa Slider #{{ $slider->id }}</h2>
    <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">
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
    <form action="{{ route('admin.sliders.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('title') is-invalid @enderror" 
                           id="title" name="title" value="{{ old('title', $slider->title) }}" required placeholder="Nhập tiêu đề slider">
                    @error('title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="description" class="form-label">Mô tả</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="4" 
                              placeholder="Nhập mô tả cho slider...">{{ old('description', $slider->description) }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="type" class="form-label">Loại slider <span class="text-danger">*</span></label>
                            <select class="form-select @error('type') is-invalid @enderror" 
                                    id="type" name="type" required>
                                <option value="home" {{ old('type', $slider->type) == 'home' ? 'selected' : '' }}>Trang chủ</option>
                                <option value="promotion" {{ old('type', $slider->type) == 'promotion' ? 'selected' : '' }}>Khuyến mãi</option>
                            </select>
                            @error('type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="order" class="form-label">Thứ tự hiển thị</label>
                            <input type="number" class="form-control @error('order') is-invalid @enderror" 
                                   id="order" name="order" value="{{ old('order', $slider->order) }}" min="0">
                            @error('order')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Số nhỏ hơn sẽ hiển thị trước</small>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="button_type" class="form-label">Chọn nút bấm</label>
                            <select class="form-select @error('button_type') is-invalid @enderror" 
                                    id="button_type" name="button_type" onchange="updateButtonFields()">
                                <option value="">-- Chọn nút bấm --</option>
                                <option value="mua_ngay" data-text="Mua Ngay" data-link="{{ route('products.soche') }}">Mua Ngay</option>
                                <option value="xem_san_pham" data-text="Xem Sản Phẩm" data-link="{{ route('products.soche') }}">Xem Sản Phẩm</option>
                                <option value="xem_them" data-text="Xem Thêm" data-link="{{ route('products.latest') }}">Xem Thêm</option>
                                <option value="xem_chi_tiet" data-text="Xem Chi Tiết" data-link="{{ route('product.detail', 1) }}">Xem Chi Tiết</option>
                                <option value="lien_he" data-text="Liên Hệ" data-link="{{ route('contact') }}">Liên Hệ</option>
                                <option value="ve_chung_toi" data-text="Về Chúng Tôi" data-link="{{ route('about') }}">Về Chúng Tôi</option>
                                <option value="doc_tin_tuc" data-text="Đọc Tin Tức" data-link="{{ route('blog.index') }}">Đọc Tin Tức</option>
                                <option value="cong_thuc_mon_ngon" data-text="Công Thức Món Ngon" data-link="{{ route('blog.congthuc') }}">Công Thức Món Ngon</option>
                                <option value="khuyen_mai" data-text="Xem Khuyến Mãi" data-link="{{ route('promotion') }}">Xem Khuyến Mãi</option>
                                <option value="san_pham_moi_nhat" data-text="Sản Phẩm Mới Nhất" data-link="{{ route('products.latest') }}">Sản Phẩm Mới Nhất</option>
                                <option value="san_pham_ban_chay" data-text="Sản Phẩm Bán Chạy" data-link="{{ route('products.best_selling') }}">Sản Phẩm Bán Chạy</option>
                                <option value="custom">Tùy chỉnh</option>
                            </select>
                            @error('button_type')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Chọn nút bấm có sẵn hoặc tùy chỉnh</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="button_text" class="form-label">Text nút bấm</label>
                            <input type="text" class="form-control @error('button_text') is-invalid @enderror" 
                                   id="button_text" name="button_text" value="{{ old('button_text', $slider->button_text) }}" placeholder="Ví dụ: Mua Ngay">
                            @error('button_text')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="link" class="form-label">Link nút bấm 1</label>
                            <input type="text" class="form-control @error('link') is-invalid @enderror" 
                                   id="link" name="link" value="{{ old('link', $slider->link) }}" placeholder="Ví dụ: /san-pham-moi-nhat hoặc {{ route('products.soche') }}">
                            @error('link')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Link sẽ tự động điền khi chọn nút bấm có sẵn</small>
                        </div>
                    </div>
                </div>

                <hr class="my-4">
                <h5 class="mb-3">Nút bấm thứ 2 (Tùy chọn)</h5>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="button_type_2" class="form-label">Chọn nút bấm 2</label>
                            <select class="form-select @error('button_type_2') is-invalid @enderror" 
                                    id="button_type_2" name="button_type_2" onchange="updateButtonFields2()">
                                <option value="">-- Chọn nút bấm --</option>
                                <option value="mua_ngay" data-text="Mua Ngay" data-link="{{ route('products.soche') }}">Mua Ngay</option>
                                <option value="xem_san_pham" data-text="Xem Sản Phẩm" data-link="{{ route('products.soche') }}">Xem Sản Phẩm</option>
                                <option value="xem_them" data-text="Xem Thêm" data-link="{{ route('products.latest') }}">Xem Thêm</option>
                                <option value="xem_chi_tiet" data-text="Xem Chi Tiết" data-link="{{ route('product.detail', 1) }}">Xem Chi Tiết</option>
                                <option value="lien_he" data-text="Liên Hệ" data-link="{{ route('contact') }}">Liên Hệ</option>
                                <option value="ve_chung_toi" data-text="Về Chúng Tôi" data-link="{{ route('about') }}">Về Chúng Tôi</option>
                                <option value="doc_tin_tuc" data-text="Đọc Tin Tức" data-link="{{ route('blog.index') }}">Đọc Tin Tức</option>
                                <option value="cong_thuc_mon_ngon" data-text="Công Thức Món Ngon" data-link="{{ route('blog.congthuc') }}">Công Thức Món Ngon</option>
                                <option value="khuyen_mai" data-text="Xem Khuyến Mãi" data-link="{{ route('promotion') }}">Xem Khuyến Mãi</option>
                                <option value="san_pham_moi_nhat" data-text="Sản Phẩm Mới Nhất" data-link="{{ route('products.latest') }}">Sản Phẩm Mới Nhất</option>
                                <option value="san_pham_ban_chay" data-text="Sản Phẩm Bán Chạy" data-link="{{ route('products.best_selling') }}">Sản Phẩm Bán Chạy</option>
                                <option value="custom">Tùy chỉnh</option>
                            </select>
                            @error('button_type_2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="button_text_2" class="form-label">Text nút bấm 2</label>
                            <input type="text" class="form-control @error('button_text_2') is-invalid @enderror" 
                                   id="button_text_2" name="button_text_2" value="{{ old('button_text_2', $slider->button_text_2) }}" placeholder="Ví dụ: Về Chúng Tôi">
                            @error('button_text_2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3">
                            <label for="link_2" class="form-label">Link nút bấm 2</label>
                            <input type="text" class="form-control @error('link_2') is-invalid @enderror" 
                                   id="link_2" name="link_2" value="{{ old('link_2', $slider->link_2) }}" placeholder="Ví dụ: /gioi-thieu hoặc {{ route('about') }}">
                            @error('link_2')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="background_color" class="form-label">Màu nền (cho promotion)</label>
                            <div class="input-group">
                                <input type="color" class="form-control form-control-color" 
                                       id="color_picker" 
                                       value="{{ old('background_color', $slider->background_color) ? (preg_match('/^#[0-9A-Fa-f]{6}$/', old('background_color', $slider->background_color)) ? old('background_color', $slider->background_color) : '#ff6b00') : '#ff6b00' }}" 
                                       title="Chọn màu"
                                       onchange="updateColorInput(this.value)">
                                <input type="text" class="form-control @error('background_color') is-invalid @enderror" 
                                       id="background_color" name="background_color" value="{{ old('background_color', $slider->background_color) }}" 
                                       placeholder="Ví dụ: #ff6b00 hoặc linear-gradient(135deg, #ff6b00 0%, #ff9800 100%)"
                                       oninput="updateColorPicker(this.value)">
                                <button type="button" class="btn btn-outline-secondary" onclick="clearBackgroundColor()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            @error('background_color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Chọn màu hoặc nhập mã màu/gradient</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="icon" class="form-label">Icon</label>
                            <div class="input-group">
                                <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                       id="icon" name="icon" value="{{ old('icon', $slider->icon) }}" placeholder="Ví dụ: fas fa-gift">
                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#iconPickerModal">
                                    <i class="fas fa-icons"></i> Chọn Icon
                                </button>
                            </div>
                            <div id="iconPreview" class="mt-2">
                                @if(old('icon', $slider->icon))
                                    <i class="{{ old('icon', $slider->icon) }} fa-2x"></i>
                                @endif
                            </div>
                            @error('icon')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Chọn icon từ danh sách hoặc nhập class FontAwesome</small>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="status" name="status" value="1" {{ old('status', $slider->status) ? 'checked' : '' }}>
                        <label class="form-check-label" for="status">
                            Hiển thị slider
                        </label>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="mb-3">
                    <label for="image" class="form-label">Hình ảnh</label>
                    <input type="file" class="form-control @error('image') is-invalid @enderror" 
                           id="image" name="image" accept="image/*" onchange="previewImage(this)">
                    @error('image')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text text-muted">Chấp nhận: JPG, PNG, GIF (tối đa 2MB)</small>
                    <div id="imagePreview" class="mt-3">
                        @if($slider->image_url)
                            <img src="{{ route('storage.serve', ['path' => $slider->image_url]) }}" 
                                 class="img-preview" 
                                 style="max-width: 100%; max-height: 300px; border-radius: 8px;">
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-4">
            <button type="submit" class="btn btn-success">
                <i class="fas fa-save"></i> Cập nhật
            </button>
            <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Hủy
            </a>
        </div>
    </form>
</div>

<!-- Icon Picker Modal -->
<div class="modal fade" id="iconPickerModal" tabindex="-1" aria-labelledby="iconPickerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="iconPickerModalLabel">Chọn Icon</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <input type="text" class="form-control" id="iconSearch" placeholder="Tìm kiếm icon...">
                </div>
                <div class="row g-2" id="iconGrid" style="max-height: 400px; overflow-y: auto;">
                    <!-- Icons will be populated by JavaScript -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function previewImage(input) {
        const preview = document.getElementById('imagePreview');
        if (input.files && input.files[0]) {
            if (input.files[0].size > 2097152) {
                alert('File quá lớn! Vui lòng chọn file nhỏ hơn 2MB.');
                input.value = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML = `<img src="${e.target.result}" class="img-preview" style="max-width: 100%; max-height: 300px; border-radius: 8px;">`;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function updateButtonFields() {
        const select = document.getElementById('button_type');
        const buttonTextInput = document.getElementById('button_text');
        const linkInput = document.getElementById('link');
        const selectedOption = select.options[select.selectedIndex];

        if (selectedOption.value === 'custom') {
            // Nếu chọn tùy chỉnh, cho phép nhập tự do
            buttonTextInput.readOnly = false;
            linkInput.readOnly = false;
        } else if (selectedOption.value !== '') {
            // Tự động điền text và link từ data attributes
            const text = selectedOption.getAttribute('data-text');
            const link = selectedOption.getAttribute('data-link');
            
            if (text) {
                buttonTextInput.value = text;
            }
            if (link) {
                linkInput.value = link;
            }
            
            buttonTextInput.readOnly = false;
            linkInput.readOnly = false;
        } else {
            // Xóa giá trị khi chọn "-- Chọn nút bấm --"
            buttonTextInput.value = '';
            linkInput.value = '';
            buttonTextInput.readOnly = false;
            linkInput.readOnly = false;
        }
    }

    function updateButtonFields2() {
        const select = document.getElementById('button_type_2');
        const buttonTextInput = document.getElementById('button_text_2');
        const linkInput = document.getElementById('link_2');
        const selectedOption = select.options[select.selectedIndex];

        if (selectedOption.value === 'custom') {
            buttonTextInput.readOnly = false;
            linkInput.readOnly = false;
        } else if (selectedOption.value !== '') {
            const text = selectedOption.getAttribute('data-text');
            const link = selectedOption.getAttribute('data-link');
            
            if (text) {
                buttonTextInput.value = text;
            }
            if (link) {
                linkInput.value = link;
            }
            
            buttonTextInput.readOnly = false;
            linkInput.readOnly = false;
        } else {
            buttonTextInput.value = '';
            linkInput.value = '';
            buttonTextInput.readOnly = false;
            linkInput.readOnly = false;
        }
    }

    function updateColorInput(color) {
        // Khi chọn màu từ color picker, cập nhật vào input text
        const bgColorInput = document.getElementById('background_color');
        if (bgColorInput && color) {
            bgColorInput.value = color;
        }
    }

    function updateColorPicker(value) {
        // Khi nhập mã màu hex vào input, cập nhật color picker nếu là mã hex hợp lệ
        const colorPicker = document.getElementById('color_picker');
        if (colorPicker && value) {
            const hexMatch = value.match(/^#([0-9A-Fa-f]{6})$/);
            if (hexMatch) {
                colorPicker.value = value;
            }
        }
    }

    function clearBackgroundColor() {
        document.getElementById('background_color').value = '';
        document.getElementById('color_picker').value = '#ff6b00';
    }

    // Tự động chọn option phù hợp khi load trang edit
    document.addEventListener('DOMContentLoaded', function() {
        const buttonText = document.getElementById('button_text').value;
        const link = document.getElementById('link').value;
        const select = document.getElementById('button_type');
        
        // Tìm option phù hợp với giá trị hiện tại
        for (let i = 0; i < select.options.length; i++) {
            const option = select.options[i];
            const optionText = option.getAttribute('data-text');
            const optionLink = option.getAttribute('data-link');
            
            if (optionText === buttonText && optionLink === link) {
                select.selectedIndex = i;
                break;
            }
        }

        // Tự động chọn option cho button 2
        const buttonText2 = document.getElementById('button_text_2').value;
        const link2 = document.getElementById('link_2').value;
        const select2 = document.getElementById('button_type_2');
        
        if (buttonText2 || link2) {
            for (let i = 0; i < select2.options.length; i++) {
                const option = select2.options[i];
                const optionText = option.getAttribute('data-text');
                const optionLink = option.getAttribute('data-link');
                
                if (optionText === buttonText2 && optionLink === link2) {
                    select2.selectedIndex = i;
                    break;
                }
            }
        }

        // Khởi tạo color picker với giá trị hiện tại
        const bgColor = document.getElementById('background_color').value;
        if (bgColor) {
            const hexMatch = bgColor.match(/^#([0-9A-Fa-f]{6})$/);
            if (hexMatch) {
                document.getElementById('color_picker').value = bgColor;
            }
        }
    });

    // Icon Picker
    const icons = [
        'fas fa-gift', 'fas fa-fire', 'fas fa-shipping-fast', 'fas fa-users',
        'fas fa-fish', 'fas fa-shopping-cart', 'fas fa-heart', 'fas fa-star',
        'fas fa-trophy', 'fas fa-medal', 'fas fa-certificate', 'fas fa-award',
        'fas fa-handshake', 'fas fa-user-friends', 'fas fa-store', 'fas fa-box',
        'fas fa-utensils', 'fas fa-book', 'fas fa-newspaper', 'fas fa-blog',
        'fas fa-info-circle', 'fas fa-question-circle', 'fas fa-check-circle',
        'fas fa-tags', 'fas fa-percent', 'fas fa-dollar-sign', 'fas fa-coins',
        'fas fa-clock', 'fas fa-calendar', 'fas fa-bell', 'fas fa-envelope',
        'fas fa-phone', 'fas fa-map-marker-alt', 'fas fa-globe', 'fas fa-home',
        'fas fa-search', 'fas fa-filter', 'fas fa-sort', 'fas fa-list',
        'fas fa-th', 'fas fa-th-large', 'fas fa-image', 'fas fa-images',
        'fas fa-camera', 'fas fa-video', 'fas fa-music', 'fas fa-film',
        'fas fa-gamepad', 'fas fa-laptop', 'fas fa-mobile-alt', 'fas fa-tablet-alt',
        'fas fa-car', 'fas fa-bicycle', 'fas fa-plane', 'fas fa-ship',
        'fas fa-train', 'fas fa-bus', 'fas fa-motorcycle', 'fas fa-walking',
        'fas fa-running', 'fas fa-swimmer', 'fas fa-dumbbell', 'fas fa-football-ball',
        'fas fa-basketball-ball', 'fas fa-volleyball-ball', 'fas fa-baseball-ball',
        'fas fa-tree', 'fas fa-leaf', 'fas fa-seedling', 'fas fa-cloud',
        'fas fa-sun', 'fas fa-moon', 'fas fa-star-and-crescent', 'fas fa-rainbow'
    ];

    function populateIcons() {
        const iconGrid = document.getElementById('iconGrid');
        iconGrid.innerHTML = '';
        
        icons.forEach(icon => {
            const col = document.createElement('div');
            col.className = 'col-2 col-md-1 text-center p-2';
            col.style.cursor = 'pointer';
            col.style.border = '1px solid #dee2e6';
            col.style.borderRadius = '4px';
            col.style.margin = '2px';
            col.style.transition = 'all 0.2s';
            col.onmouseover = function() {
                this.style.backgroundColor = '#f8f9fa';
                this.style.transform = 'scale(1.1)';
            };
            col.onmouseout = function() {
                this.style.backgroundColor = '';
                this.style.transform = 'scale(1)';
            };
            col.onclick = function() {
                selectIcon(icon);
            };
            
            const iconEl = document.createElement('i');
            iconEl.className = icon + ' fa-2x';
            iconEl.style.color = '#0066cc';
            
            col.appendChild(iconEl);
            iconGrid.appendChild(col);
        });
    }

    function selectIcon(iconClass) {
        document.getElementById('icon').value = iconClass;
        document.getElementById('iconPreview').innerHTML = `<i class="${iconClass} fa-2x"></i>`;
        const modal = bootstrap.Modal.getInstance(document.getElementById('iconPickerModal'));
        modal.hide();
    }

    // Search icons
    document.getElementById('iconSearch')?.addEventListener('input', function(e) {
        const searchTerm = e.target.value.toLowerCase();
        const iconItems = document.querySelectorAll('#iconGrid > div');
        
        iconItems.forEach(item => {
            const iconClass = item.querySelector('i').className;
            if (iconClass.toLowerCase().includes(searchTerm)) {
                item.style.display = '';
            } else {
                item.style.display = 'none';
            }
        });
    });

    // Update preview when typing
    document.getElementById('icon')?.addEventListener('input', function(e) {
        const iconClass = e.target.value;
        const preview = document.getElementById('iconPreview');
        if (iconClass) {
            preview.innerHTML = `<i class="${iconClass} fa-2x"></i>`;
        } else {
            preview.innerHTML = '';
        }
    });

    // Populate icons when modal opens
    document.getElementById('iconPickerModal')?.addEventListener('show.bs.modal', function() {
        populateIcons();
    });
</script>
@endpush
@endsection

