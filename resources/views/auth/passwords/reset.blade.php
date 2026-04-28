@extends('layouts.app')
@section('title', 'تعيين كلمة مرور جديدة')
@push('css')
    <link rel="stylesheet" href="{{ asset('css') }}/auth.css">
@endpush
@section('content')
    <main class="main-content bg-light">
        <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
            <div class="login-card">
                <div class="login-header">
                    <h3>تعيين كلمة مرور جديدة</h3>
                </div>
                <div class="login-body">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">
                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ $request->email ?? old('email') }}" required autofocus>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">كلمة المرور الجديدة</label>
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" required>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="password-confirm" class="form-label">تأكيد كلمة المرور</label>
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                                required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">إعادة تعيين كلمة المرور</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
