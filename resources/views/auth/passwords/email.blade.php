@extends('layouts.app')
@section('title', 'إعادة تعيين كلمة المرور')
@push('css')
    <link rel="stylesheet" href="{{ asset('css') }}/auth.css">
@endpush
@section('content')
    <main class="main-content bg-light">
        <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
            <div class="login-card">
                <div class="login-header">
                    <h3>هل نسيت كلمة المرور؟</h3>
                    <p>لا مشكلة. أدخل بريدك الإلكتروني وسنرسل لك رابطًا لإعادة التعيين.</p>
                </div>
                <div class="login-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('password.email') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label">البريد الإلكتروني</label>
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                                name="email" value="{{ old('email') }}" required autofocus>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">إرسال رابط إعادة التعيين</button>
                        </div>
                        <div class="text-center mt-4">
                            <a href="{{ route('login') }}">العودة لتسجيل الدخول</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
