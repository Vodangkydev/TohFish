@extends('layouts.app')

@section('title', 'Chi tiết tuyển dụng - TOH fish')

@section('content')
@php
    $job = $jobs->first();
@endphp
<section class="job-detail-section py-5">
    <div class="container">
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item"><a href="{{ route('tuyen-dung') }}">Tuyển dụng</a></li>
                <li class="breadcrumb-item active" aria-current="page">Chi tiết tuyển dụng</li>
            </ol>
        </nav>

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

        @if($job)
        <div class="row">
            <!-- Thông tin công việc -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="card-title mb-4">{{ $jobDetail->vi_tri ?? 'Vị trí tuyển dụng' }}</h1>
                        
                        @if($jobDetail)
                        <div class="job-info mb-4">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-map-marker-alt text-primary"></i> Nơi làm việc:</strong> 
                                       {{ $jobDetail->workplace ?? 'Chưa cập nhật' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-calendar text-primary"></i> Số lượng:</strong> 
                                       {{ $jobDetail->total ?? 'Chưa cập nhật' }} người</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-clock text-primary"></i> Ngày làm việc:</strong> 
                                       {{ $jobDetail->workday ? date('d/m/Y', strtotime($jobDetail->workday)) : 'Chưa cập nhật' }}</p>
                                </div>
                                <div class="col-md-6">
                                    <p><strong><i class="fas fa-hourglass-half text-primary"></i> Giờ làm việc:</strong> 
                                       {{ $jobDetail->business_hours ?? 'Chưa cập nhật' }}</p>
                                </div>
                            </div>

                            @if($jobDetail->work_address)
                            <p><strong><i class="fas fa-building text-primary"></i> Địa chỉ:</strong> {{ $jobDetail->work_address }}</p>
                            @endif
                        </div>

                        <hr>

                        @if($jobDetail->job_description)
                        <div class="job-description mb-4">
                            <h3 class="mb-3">Mô tả công việc</h3>
                            <div class="text-muted">
                                {!! nl2br(e($jobDetail->job_description)) !!}
                            </div>
                        </div>
                        @endif

                        @if($jobDetail->request)
                        <div class="job-requirements mb-4">
                            <h3 class="mb-3">Yêu cầu</h3>
                            <div class="text-muted">
                                {!! nl2br(e($jobDetail->request)) !!}
                            </div>
                        </div>
                        @endif

                        @if($jobDetail->interest)
                        <div class="job-benefits mb-4">
                            <h3 class="mb-3">Quyền lợi</h3>
                            <div class="text-muted">
                                {!! nl2br(e($jobDetail->interest)) !!}
                            </div>
                        </div>
                        @endif

                        @if($jobDetail->age || $jobDetail->level)
                        <div class="job-qualifications mb-4">
                            <h3 class="mb-3">Tiêu chuẩn</h3>
                            <ul class="list-unstyled">
                                @if($jobDetail->age)
                                <li><i class="fas fa-check text-success"></i> Tuổi: {{ $jobDetail->age }}</li>
                                @endif
                                @if($jobDetail->level)
                                <li><i class="fas fa-check text-success"></i> Trình độ: {{ $jobDetail->level }}</li>
                                @endif
                            </ul>
                        </div>
                        @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Form gửi CV -->
            <div class="col-lg-4">
                <div class="card shadow-sm sticky-top" style="top: 100px;">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-paper-plane"></i> Ứng tuyển ngay</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('upload', $job->job_id) }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="job_id" value="{{ $job->job_id }}">

                            <div class="mb-3">
                                <label for="ho_ten" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('ho_ten') is-invalid @enderror" 
                                       id="ho_ten" name="ho_ten" value="{{ old('ho_ten') }}" required>
                                @error('ho_ten')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="age" class="form-label">Tuổi <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('age') is-invalid @enderror" 
                                           id="age" name="age" value="{{ old('age') }}" min="18" max="100" required>
                                    @error('age')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
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

                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                       id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Số điện thoại <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                       id="phone" name="phone" value="{{ old('phone') }}" required>
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="current_residence" class="form-label">Nơi ở hiện tại <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('current_residence') is-invalid @enderror" 
                                       id="current_residence" name="current_residence" value="{{ old('current_residence') }}" required>
                                @error('current_residence')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="place_of_birth" class="form-label">Nơi sinh <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('place_of_birth') is-invalid @enderror" 
                                       id="place_of_birth" name="place_of_birth" value="{{ old('place_of_birth') }}" required>
                                @error('place_of_birth')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                                <label for="url_facebook" class="form-label">Link Facebook</label>
                                <input type="url" class="form-control @error('url_facebook') is-invalid @enderror" 
                                       id="url_facebook" name="url_facebook" value="{{ old('url_facebook') }}" placeholder="https://facebook.com/...">
                                @error('url_facebook')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="willing_to_travel" 
                                           name="willing_to_travel" value="1" {{ old('willing_to_travel') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="willing_to_travel">
                                        Sẵn sàng đi công tác
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="willing_to_work_overtime" 
                                           name="willing_to_work_overtime" value="1" {{ old('willing_to_work_overtime') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="willing_to_work_overtime">
                                        Sẵn sàng làm thêm giờ
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="previous_experiences" class="form-label">Kinh nghiệm làm việc</label>
                                <textarea class="form-control @error('previous_experiences') is-invalid @enderror" 
                                          id="previous_experiences" name="previous_experiences" rows="3">{{ old('previous_experiences') }}</textarea>
                                @error('previous_experiences')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="personal_experience" class="form-label">Kỹ năng cá nhân</label>
                                <textarea class="form-control @error('personal_experience') is-invalid @enderror" 
                                          id="personal_experience" name="personal_experience" rows="3">{{ old('personal_experience') }}</textarea>
                                @error('personal_experience')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="file_path" class="form-label">Upload CV (PDF, DOC, DOCX) <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('file_path') is-invalid @enderror" 
                                       id="file_path" name="file_path" accept=".pdf,.doc,.docx" required>
                                <small class="form-text text-muted">Tối đa 10MB</small>
                                @error('file_path')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-paper-plane"></i> Gửi CV
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div class="alert alert-warning">
            Không tìm thấy thông tin tuyển dụng.
        </div>
        @endif
    </div>
</section>
@endsection

