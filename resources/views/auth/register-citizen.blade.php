@extends('layouts.app')

@section('title', 'تسجيل مواطن جديد')
@push('css')
    <link rel="stylesheet" href="{{ asset('css') }}/auth.css">
@endpush
@section('content')
    <main class="main-content bg-light">
        {{-- Page Header --}}
        <header class="page-header" style="background-image: url('{{ asset('images/bg/register.jpg') }}');">
            <div class="container text-center">
                <h1>إنشاء حساب مستخدم</h1>
                <p>انضم إلى بوابتنا الرقمية للاستفادة من الخدمات الإلكترونية والتفاعل مع الجهات الحكومية.</p>
            </div>
        </header>

        <div class="container py-5">
            <div class="form-wrapper-container mx-auto" style="max-width: 900px;">
                <div class="form-card registration-form">
                    <div class="card-header">
                        <div class="header-icon"><i class="fas fa-user-plus"></i></div>
                        <h3>تسجيل حساب جديد</h3>
                        <p>يرجى إدخال بياناتك بشكل صحيح كما هي مدونة في بطاقة الرقم القومي.</p>
                    </div>
                    <div class="card-body">

                        @if ($errors->any())
                            <div class="alert alert-danger mb-4">
                                <strong>خطأ في البيانات!</strong> يرجى مراجعة الحقول وإصلاح الأخطاء.
                            </div>
                        @endif

                        <form action="{{ route('register.citizen.store') }}" method="POST" enctype="multipart/form-data"
                            class="needs-validation" novalidate>
                            @csrf

                            {{-- Step 1: Personal Information --}}
                            <div class="form-step">
                                <h5 class="form-section-title"><span>1</span> البيانات الشخصية</h5>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="name" class="form-label">الاسم الكامل (رباعي) <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="name" name="name"
                                            class="form-control @error('name') is-invalid @enderror"
                                            value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">البريد الإلكتروني
                                        </label>
                                        <input type="email" id="email" name="email"
                                            class="form-control @error('email') is-invalid @enderror"
                                            value="{{ old('email') }}">
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">رقم الهاتف <span
                                                class="text-danger">*</span></label>
                                        <input type="tel" id="phone" name="phone"
                                            class="form-control @error('phone') is-invalid @enderror"
                                            value="{{ old('phone') }}" required>
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="address" class="form-label">العنوان (كما هو مسجل بالبطاقة)</label>
                                        <input type="text" id="address" name="address"
                                            class="form-control @error('address') is-invalid @enderror"
                                            value="{{ old('address') }}">
                                        @error('address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label for="job_title" class="form-label">الوظيفة (اختياري)</label>
                                        <input type="text" id="job_title" name="job_title"
                                            class="form-control @error('job_title') is-invalid @enderror"
                                            value="{{ old('job_title') }}">
                                    </div>
                                </div>
                            </div>

                            {{-- Step 2: Account Information --}}
                            <div class="form-step">
                                <h5 class="form-section-title"><span>2</span> بيانات الحساب</h5>
                                <div class="row">
                                    <div class="col-md-12 mb-3">
                                        <label for="national_id" class="form-label">الرقم القومي (14 رقم) <span
                                                class="text-danger">*</span></label>
                                        <input type="text" id="national_id" name="national_id"
                                            class="form-control @error('national_id') is-invalid @enderror" pattern="\d{14}"
                                            title="يجب إدخال 14 رقمًا" value="{{ old('national_id') }}" required>
                                        @error('national_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- In register-citizen.blade.php --}}

                                    <div class="col-md-6 mb-3">
                                        <label for="password" class="form-label">كلمة المرور <span
                                                class="text-danger">*</span></label>
                                        <input type="password" id="password" name="password"
                                            class="form-control @error('password') is-invalid @enderror" required>

                                        {{-- Display all password-related errors --}}
                                        @error('password')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @else
                                            <small class="form-text text-muted">يجب أن تحتوي على 8 أحرف على الأقل، مع حروف كبيرة
                                                وصغيرة وأرقام ورموز.</small>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور <span
                                                class="text-danger">*</span></label>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-control" required>
                                    </div>
                                </div>
                            </div>

                            {{-- Step 3: Document Upload --}}
                            <div class="form-step">
                                <h5 class="form-section-title"><span>3</span> إثبات الهوية</h5>
                                <div class="mb-4">
                                    <label for="national_id_image" class="form-label">صورة واضحة من بطاقة الرقم القومي
                                        (وجه أمامي) <span class="text-danger">*</span></label>
                                    <input type="file" id="national_id_image" name="national_id_image"
                                        class="form-control @error('national_id_image') is-invalid @enderror" required>
                                    <small class="form-text text-muted">سيتم استخدام هذه الصورة لمراجعة وتفعيل حسابك.
                                        الأنواع المسموح بها: JPG, PNG.</small>
                                    @error('national_id_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-grid mt-4">
                                <button type="submit" class="btn btn-primary btn-lg">إنشاء الحساب</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
