@extends('layouts.app')

@section('title', 'Hồ sơ của tôi')

@section('content')
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-1">Hồ sơ người dùng</h2>
                        <p class="text-muted mb-0">Xem thông tin tài khoản của bạn.</p>
                    </div>
                    <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">
                        Chỉnh sửa hồ sơ
                    </a>
                </div>
            </div>
        </div>

        <div class="row g-4 justify-content-center">
            <div class="col-md-10 col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('status') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <h5 class="card-title mb-3">Thông tin tài khoản</h5>
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Tên:</strong> {{ $user->name }}</p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
                            </div>
                        </div>
                        @if($user->phone || $user->address)
                        <div class="row mb-2">
                            @if($user->phone)
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Số điện thoại:</strong> {{ $user->phone }}</p>
                            </div>
                            @endif
                            @if($user->address)
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Địa chỉ:</strong> {{ $user->address }}</p>
                            </div>
                            @endif
                        </div>
                        @endif
                        @if($user->city || $user->district)
                        <div class="row mb-2">
                            @if($user->city)
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Tỉnh/Thành:</strong> {{ $user->city }}</p>
                            </div>
                            @endif
                            @if($user->district)
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Quận/Huyện:</strong> {{ $user->district }}</p>
                            </div>
                            @endif
                        </div>
                        @endif
                        <div class="row mb-2">
                            <div class="col-md-6">
                                <p class="mb-1"><strong>Ngày tạo:</strong> {{ $user->created_at?->format('d/m/Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection


