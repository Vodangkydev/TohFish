@extends('layouts.app')

@section('title', 'Tuyển Dụng - TOH fish')

@section('content')
<section class="recruitment-section py-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-title text-center mb-5">Tuyển Dụng</h1>
                <p class="text-center text-muted mb-5">Cơ hội nghề nghiệp tại TOH fish</p>
            </div>
        </div>
        
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="recruitment-info bg-light p-5 rounded shadow mb-5">
                    <h3 class="mb-4">Cơ Hội Nghề Nghiệp</h3>
                    <p>TOH fish đang tìm kiếm những tài năng để cùng phát triển. Chúng tôi cung cấp môi trường làm việc chuyên nghiệp và cơ hội thăng tiến.</p>
                    
                    <h4 class="mt-4 mb-3">Vị Trí Đang Tuyển:</h4>
                    @if($jobPositions && $jobPositions->count() > 0)
                        <ul class="list-unstyled">
                            @foreach($jobPositions as $position)
                                <li class="mb-2">
                                    <a href="{{ route('job-position.detail', $position->id) }}" class="text-decoration-none d-flex align-items-center justify-content-between p-3 bg-white rounded shadow-sm position-item">
                                        <strong class="text-dark">{{ $position->title }}</strong>
                                        <i class="fas fa-chevron-right text-muted"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <ul>
                            <li>Nhân viên kinh doanh</li>
                            <li>Nhân viên chế biến thực phẩm</li>
                            <li>Nhân viên kho bãi</li>
                            <li>Nhân viên giao hàng</li>
                            <li>Kế toán viên</li>
                        </ul>
                    @endif
                    
                    <div class="mt-4">
                        <h5>Gửi CV qua email:</h5>
                        <p><i class="fas fa-envelope"></i> <a href="mailto:contact@tohfish.com">contact@tohfish.com</a></p>
                        <p><i class="fas fa-phone"></i> Hotline: (+84) 999 99 9999</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form nộp CV -->
        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-paper-plane"></i> Nộp CV Ứng Tuyển</h4>
                    </div>
                    <div class="card-body p-4">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="ho_ten" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('ho_ten') is-invalid @enderror" 
                                           id="ho_ten" name="ho_ten" value="{{ old('ho_ten') }}" required>
                                    @error('ho_ten')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="age" class="form-label">Tuổi <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('age') is-invalid @enderror" 
                                           id="age" name="age" value="{{ old('age') }}" min="18" max="100" required>
                                    @error('age')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-3 mb-3">
                                    <label for="sex" class="form-label">Giới tính <span class="text-danger">*</span></label>
                                    <select class="form-select @error('sex') is-invalid @enderror" id="sex" name="sex" required>
                                        <option value="">Chọn</option>
                                        <option value="male" {{ old('sex') == 'male' ? 'selected' : '' }}>Nam</option>
                                        <option value="female" {{ old('sex') == 'female' ? 'selected' : '' }}>Nữ</option>
                                        <option value="other" {{ old('sex') == 'other' ? 'selected' : '' }}>Khác</option>
                                    </select>
                                    @error('sex')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="current_residence" class="form-label">Nơi ở hiện tại <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('current_residence') is-invalid @enderror" 
                                           id="current_residence" name="current_residence" value="{{ old('current_residence') }}" required>
                                    @error('current_residence')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="place_of_birth" class="form-label">Nơi sinh <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control @error('place_of_birth') is-invalid @enderror" 
                                           id="place_of_birth" name="place_of_birth" value="{{ old('place_of_birth') }}" required>
                                    @error('place_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="level" class="form-label">Trình độ học vấn <span class="text-danger">*</span></label>
                                <select class="form-select @error('level') is-invalid @enderror" id="level" name="level" required>
                                    <option value="">Chọn trình độ</option>
                                    <option value="Trung cấp" {{ old('level') == 'Trung cấp' ? 'selected' : '' }}>Trung cấp</option>
                                    <option value="Cao đẳng" {{ old('level') == 'Cao đẳng' ? 'selected' : '' }}>Cao đẳng</option>
                                    <option value="Đại học" {{ old('level') == 'Đại học' ? 'selected' : '' }}>Đại học</option>
                                    <option value="Sau đại học" {{ old('level') == 'Sau đại học' ? 'selected' : '' }}>Sau đại học</option>
                                </select>
                                @error('level')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="applied_position" class="form-label">Vị Trí Đang Tuyển <span class="text-danger">*</span></label>
                                <select class="form-select @error('applied_position') is-invalid @enderror" id="applied_position" name="applied_position" required>
                                    <option value="">Chọn vị trí</option>
                                    @if($jobPositions && $jobPositions->count() > 0)
                                        @foreach($jobPositions as $position)
                                            <option value="{{ $position->title }}" {{ old('applied_position') == $position->title ? 'selected' : '' }}>
                                                {{ $position->title }}
                                            </option>
                                        @endforeach
                                    @else
                                        <option value="Nhân viên kinh doanh" {{ old('applied_position') == 'Nhân viên kinh doanh' ? 'selected' : '' }}>Nhân viên kinh doanh</option>
                                        <option value="Nhân viên chế biến thực phẩm" {{ old('applied_position') == 'Nhân viên chế biến thực phẩm' ? 'selected' : '' }}>Nhân viên chế biến thực phẩm</option>
                                        <option value="Nhân viên kho bãi" {{ old('applied_position') == 'Nhân viên kho bãi' ? 'selected' : '' }}>Nhân viên kho bãi</option>
                                        <option value="Nhân viên giao hàng" {{ old('applied_position') == 'Nhân viên giao hàng' ? 'selected' : '' }}>Nhân viên giao hàng</option>
                                        <option value="Kế toán viên" {{ old('applied_position') == 'Kế toán viên' ? 'selected' : '' }}>Kế toán viên</option>
                                    @endif
                                </select>
                                @error('applied_position')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="willing_to_travel" 
                                               name="willing_to_travel" value="1" {{ old('willing_to_travel') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="willing_to_travel">
                                            Sẵn sàng đi công tác
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="willing_to_work_overtime" 
                                               name="willing_to_work_overtime" value="1" {{ old('willing_to_work_overtime') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="willing_to_work_overtime">
                                            Sẵn sàng làm thêm giờ
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="previous_experiences" class="form-label">Kinh nghiệm làm việc</label>
                                <textarea class="form-control @error('previous_experiences') is-invalid @enderror" 
                                          id="previous_experiences" name="previous_experiences" rows="3" placeholder="Mô tả kinh nghiệm làm việc của bạn...">{{ old('previous_experiences') }}</textarea>
                                @error('previous_experiences')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="personal_experience" class="form-label">Kỹ năng cá nhân</label>
                                <textarea class="form-control @error('personal_experience') is-invalid @enderror" 
                                          id="personal_experience" name="personal_experience" rows="3" placeholder="Mô tả kỹ năng cá nhân của bạn...">{{ old('personal_experience') }}</textarea>
                                @error('personal_experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-4">
                                <label for="file_path" class="form-label">Upload CV (PDF, DOC, DOCX) <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('file_path') is-invalid @enderror" 
                                       id="file_path" name="file_path" accept=".pdf,.doc,.docx" required>
                                <small class="form-text text-muted">Tối đa 10MB</small>
                                @error('file_path')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">
                                    <i class="fas fa-paper-plane"></i> Gửi CV
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    .position-item {
        transition: all 0.3s ease;
        border-left: 3px solid transparent;
    }
    
    .position-item:hover {
        background-color: #f8f9fa !important;
        border-left-color: #0066cc;
        transform: translateX(5px);
    }
    
    .position-item:hover strong {
        color: #0066cc;
    }
</style>
@endpush
@endsection

