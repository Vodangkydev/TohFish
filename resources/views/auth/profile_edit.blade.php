@extends('layouts.app')

@section('title', 'Chỉnh sửa hồ sơ')

@section('content')
@php
    $cities = [
        'TP. Hồ Chí Minh',
        'Hà Nội',
        'Đồng Nai',
        'Bình Dương',
        'Khác',
    ];
@endphp
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12 d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="mb-1">Chỉnh sửa hồ sơ</h2>
                    <p class="text-muted mb-0">Cập nhật thông tin tài khoản của bạn.</p>
                </div>
                <a href="{{ route('profile') }}" class="btn btn-outline-secondary btn-sm">
                    Quay lại hồ sơ
                </a>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <form method="POST" action="{{ route('profile.update') }}">
                            @csrf

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Họ và tên</label>
                                    <input type="text" name="name" id="name"
                                           class="form-control @error('name') is-invalid @enderror"
                                           value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" name="email" id="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="phone" class="form-label">Số điện thoại</label>
                                    <input type="text" name="phone" id="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone', $user->phone) }}"
                                           placeholder="Ví dụ: 0909123456">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="address" class="form-label">Địa chỉ</label>
                                    <input type="text" name="address" id="address"
                                           class="form-control @error('address') is-invalid @enderror"
                                           value="{{ old('address', $user->address) }}"
                                           placeholder="Số nhà, đường, phường/xã">
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row g-3 mt-1">
                                <div class="col-md-6">
                                    <label for="city" class="form-label">Tỉnh/Thành phố</label>
                                    <select name="city" id="city" class="form-select @error('city') is-invalid @enderror">
                                        <option value="">Chọn tỉnh/thành phố</option>
                                        @foreach ($cities as $city)
                                            <option value="{{ $city }}" @selected(old('city', $user->city) === $city)>{{ $city }}</option>
                                        @endforeach
                                    </select>
                                    @error('city')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="district" class="form-label">Quận/Huyện</label>
                                    <input type="text" name="district" id="district"
                                           class="form-control @error('district') is-invalid @enderror"
                                           value="{{ old('district', $user->district) }}"
                                           placeholder="Ví dụ: Quận 1, Thủ Đức">
                                    @error('district')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mt-4">
                                <button type="submit" class="btn btn-primary">
                                    Lưu thay đổi
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


