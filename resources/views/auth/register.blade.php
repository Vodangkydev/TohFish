@extends('layouts.app')

@section('title', 'Đăng ký')

@section('content')
<section class="py-5">
    <div class="container" style="max-width: 480px;">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="mb-3 text-center">Đăng ký</h3>
                <p class="text-muted text-center mb-3">Tạo tài khoản mới để đặt hàng và theo dõi thông tin.</p>
                @if ($errors->any())
                    <div class="alert alert-danger small">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('register.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Tên hiển thị</label>
                        <input name="name" type="text" class="form-control" placeholder="Tên của bạn" value="{{ old('name') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input name="email" type="email" class="form-control" placeholder="you@example.com" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu</label>
                        <input name="password" type="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Xác nhận mật khẩu</label>
                        <input name="password_confirmation" type="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Đăng ký</button>
                </form>
                <div class="text-center mt-3">
                    <small>Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a></small>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

