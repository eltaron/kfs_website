@extends('layouts.app')

@section('title', 'تواصل معنا')
@push('css')
    <link rel="stylesheet" href="{{ asset('css') }}/contact.css">
@endpush
@section('content')
    <main class="main-content">
        <header class="page-header" style="background-image: url('{{ asset('images/bg/contact.jpeg') }}');">
            <div class="container text-center">
                <h1>تواصل معنا</h1>
                <p>نحن هنا للاستماع إلى استفساراتك واقتراحاتك.</p>
            </div>
        </header>

        <div class="container py-5">
            <div class="contact-wrapper">
                <div class="row g-0">
                    {{-- Form Column --}}
                    <div class="col-lg-7">
                        <div class="contact-form-container">
                            <h3>أرسل لنا رسالة</h3>
                            <p class="mb-4">املأ النموذج أدناه وسيقوم فريقنا بالتواصل معك.</p>

                            @if (session('success'))
                                <div class="alert alert-success">{{ session('success') }}</div>
                            @endif

                            @if ($errors->any())
                                <div class="alert alert-danger">يرجى تصحيح الأخطاء أدناه.</div>
                            @endif

                            <form action="{{ route('contact.store') }}" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="name" class="form-label">الاسم الكامل</label>
                                        <input type="text" id="name" name="name" class="form-control"
                                            value="{{ old('name', auth()->check() ? auth()->user()->name : '') }}"
                                            {{ auth()->check() ? '' : '' }}>

                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="email" class="form-label">البريد الإلكتروني</label>
                                        <input type="email" id="email" name="email" class="form-control"
                                            value="{{ old('email', auth()->check() ? auth()->user()->email : '') }}"
                                            {{ auth()->check() ? '' : '' }}>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="phone" class="form-label">رقم الهاتف (اختياري)</label>
                                        <input type="tel" id="phone" name="phone" class="form-control"
                                            value="{{ old('phone', auth()->check() ? auth()->user()->phone : '') }}">

                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="subject" class="form-label">الموضوع</label>
                                        <input type="text" id="subject" name="subject" class="form-control"
                                            value="{{ old('subject') }}" required>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="message" class="form-label">نص الرسالة</label>
                                    <textarea id="message" name="message" class="form-control" rows="6" required>{{ old('message') }}</textarea>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">إرسال الرسالة</button>
                            </form>
                        </div>
                    </div>

                    {{-- Info Column --}}
                    <div class="col-lg-5">
                        <div class="contact-info-container">
                            <h3>معلومات الاتصال</h3>
                            <p>يمكنك أيضًا التواصل معنا مباشرة عبر القنوات التالية:</p>
                            <ul class="info-list">
                                <li>
                                    <i class="fas fa-map-marker-alt"></i>
                                    <div>
                                        <strong>العنوان</strong>
                                        <span>مبنى ديوان عام المحافظة، كفر الشيخ، مصر</span>
                                    </div>
                                </li>
                                {{-- <li>
                                    <i class="fas fa-phone-alt"></i>
                                    <div>
                                        <strong>الهاتف</strong>
                                        <span>+20 XX XXXX XXXX</span>
                                    </div>
                                </li>
                                <li>
                                    <i class="fas fa-envelope"></i>
                                    <div>
                                        <strong>البريد الإلكتروني</strong>
                                        <span>info@kfs.gov.eg</span>
                                    </div>
                                </li> --}}
                                <li>
                                    <i class="fas fa-clock"></i>
                                    <div>
                                        <strong>مواعيد العمل</strong>
                                        <span>الأحد - الخميس | 9 صباحًا - 3 مساءً</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
