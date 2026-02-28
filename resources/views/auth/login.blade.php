@extends('layouts.app')

@section('title', 'Đăng nhập')

@section('content')
<section class="py-5">
    <div class="container" style="max-width: 420px;">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="mb-3 text-center">Đăng nhập</h3>
                <p class="text-muted text-center mb-3">Đăng nhập bằng tài khoản đã đăng ký.</p>
                @if ($errors->any())
                    <div class="alert alert-danger small">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form method="POST" action="{{ route('login.post') }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input name="email" type="email" class="form-control" placeholder="you@example.com" value="{{ old('email') }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu</label>
                        <input name="password" type="password" class="form-control" placeholder="••••••••" required>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">Ghi nhớ</label>
                        </div>
                        <a href="#" class="small text-primary">Quên mật khẩu?</a>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Đăng nhập</button>
                </form>
                <div class="text-center mt-3">
                    <small>Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký</a></small>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

