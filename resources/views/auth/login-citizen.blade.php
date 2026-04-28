@extends('layouts.app')
@section('title', 'تسجيل الدخول')
@push('css')
    <link rel="stylesheet" href="{{ asset('css') }}/auth.css">
@endpush
@section('content')
    <main class="main-content bg-light py-5">
        <div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
            <div class="login-card">
                <div class="login-header">
                    <h3>تسجيل الدخول إلى البوابة</h3>
                    <p>استخدم الرقم القومي وكلمة المرور للوصول إلى حسابك.</p>
                </div>
                <div class="login-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            {{ $errors->first() }}
                        </div>
                    @endif
                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        {{-- National ID Field --}}
                        <div class="mb-3">
                            <label for="national_id" class="form-label">الرقم القومي</label>
                            <input id="national_id" type="text" class="form-control" name="national_id"
                                value="{{ old('national_id') }}" required autofocus>
                        </div>
                        {{-- Password Field --}}
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <label for="password" class="form-label">كلمة المرور</label>
                                {{-- Optional: Add a 'Forgot Password' link --}}
                                @if (Route::has('password.request'))
                                    <a class="form-text" href="{{ route('password.request') }}">
                                        نسيت كلمة المرور؟
                                    </a>
                                @endif
                            </div>
                            <input id="password" type="password" class="form-control" name="password" required>
                        </div>
                        {{-- Remember Me --}}
                        <div class="mb-3 form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                تذكرني
                            </label>
                        </div>
                        {{-- Submit Button --}}
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                تسجيل الدخول
                            </button>
                        </div>
                        <div class="text-center mt-4">
                            <p class="text-muted">ليس لديك حساب؟ <a href="{{ route('register.citizen') }}">أنشئ حسابًا
                                    جديدًا</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection
