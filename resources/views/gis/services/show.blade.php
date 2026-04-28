@extends('layouts.app')
@section('title', $service->name)

@push('css')
    <link rel="stylesheet" href="{{ asset('css/gis-services.css') }}">
@endpush

@section('content')
    @php
        // الحسبة المالية الدقيقة صـ 40 في دليل الـ GIS
        $basePrice = (float) $service->base_price;
        $vat = $basePrice * 0.14; // ضريبة القيمة المضافة
        $martyrStamp = 5.0; // دمغة الشهداء ثابتة
        $smsFee = 10.0; // مقابل خدمة الرسائل ثابتة
        $totalAmount = $basePrice + $vat + $martyrStamp + $smsFee;
    @endphp
    <main class="gis-detail-page">
        {{-- هيدر الخدمة --}}
        <header class="service-premium-header">
            {{-- إضافة طبقة زخرفية خلفية هادئة --}}
            <div class="header-pattern-overlay"></div>

            <div class="container position-relative" style="z-index: 5;">
                <div class="row align-items-center">
                    {{-- الجانب الأيمن: العناوين --}}
                    <div class="col-md-8 text-center text-md-end animate__animated animate__fadeInRight">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb mb-2 custom-breadcrumb">
                                <li class="breadcrumb-item"><a href="/">الرئيسية</a></li>
                                <li class="breadcrumb-item"><a href="#">منظومة الخدمات المكانية
                                    </a></li>
                                <li class="breadcrumb-item active text-gold-soft">{{ $service->serviceType->name }}</li>
                            </ol>
                        </nav>
                        <h1 class="service-title-big fw-black">{{ $service->name }}</h1>
                    </div>

                    {{-- الجانب الأيسر: كارت السعر --}}
                    <div class="col-md-4 mt-4 mt-md-0 animate__animated animate__fadeInLeft">
                        <div class="pricing-premium-statement shadow-lg">
                            {{-- بند المقابل التفصيلي --}}
                            <div class="breakdown-rows px-4 pt-4">
                                <div class="price-row">
                                    <span class="p-label">المقابل الفني</span>
                                    <span class="p-value">{{ number_format($basePrice, 2) }} ج.م</span>
                                </div>
                                <div class="price-row">
                                    <span class="p-label small">ضريبة القيمة المضافة (14%)</span>
                                    <span class="p-value">+ {{ number_format($vat, 2) }} ج.م</span>
                                </div>
                                <div class="price-row">
                                    <span class="p-label">دمغة الشهداء (مساحة)</span>
                                    <span class="p-value">5.00 ج.م</span>
                                </div>
                                <div class="price-row last-row">
                                    <span class="p-label text-warning">مقابل المتابعة (SMS)</span>
                                    <span class="p-value">10.00 ج.م</span>
                                </div>
                            </div>

                            {{-- المجموع النهائي --}}
                            <div class="total-receipt-footer bg-gold-gradient">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="text-navy text-start">
                                        <div class="fw-bold mb-0">إجمالي مقابل الخدمة</div>
                                        <small class="opacity-75">المطالبة الرقمية المعتمدة</small>
                                    </div>
                                    <div class="final-total-amount text-navy">
                                        <span class="currency">ج.م</span>
                                        <span class="amount-number fw-black">{{ number_format($totalAmount, 2) }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="pricing-accent-line"></div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="container py-5">
            <div class="row g-4">

                {{-- العمود الأيمن: المحتوى والتفاعل --}}
                <div class="col-lg-8">

                    {{-- 3. الشروط والمتطلبات --}}
                    <div class="tabs-modern-container">
                        <ul class="nav nav-tabs border-0 gap-2 mb-4" id="serviceTabs" role="tablist">
                            {{-- التبويب الأول: التعريف بالخدمة --}}
                            <li class="nav-item">
                                <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#definition"
                                    type="button">
                                    <i class="fas fa-info-circle ms-2"></i> التعريف بالخدمة
                                </button>
                            </li>

                            {{-- التبويب الثاني: المستندات --}}
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#requirements" type="button">
                                    <i class="fas fa-file-alt ms-2"></i> المستندات المطلوبة
                                </button>
                            </li>

                            {{-- التبويب الثالث: الشروط --}}
                            <li class="nav-item">
                                <button class="nav-link" data-bs-toggle="tab" data-bs-target="#terms" type="button">
                                    <i class="fas fa-gavel ms-2"></i> الشروط والأحكام
                                </button>
                            </li>
                        </ul>

                        <div class="tab-content bg-white p-4 rounded-4 shadow-sm border border-light min-height-200">

                            {{-- محتوى التعريف بالخدمة --}}
                            <div class="tab-pane fade show active" id="definition" role="tabpanel">
                                <div class="service-content-wrapper" style="text-align: justify;">
                                    {{-- نستخدم حقل الوصف أو نبذة من الموديل --}}
                                    {!! $service->description !!}
                                </div>
                            </div>

                            {{-- محتوى المستندات المطلوبة --}}
                            <div class="tab-pane fade" id="requirements" role="tabpanel">
                                <div class="requirements-list">
                                    {!! $service->requirements !!}
                                </div>
                            </div>

                            {{-- محتوى الشروط والأحكام --}}
                            <div class="tab-pane fade" id="terms" role="tabpanel">
                                <div class="terms-content">
                                    {!! $service->terms_conditions !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- 4. إقرار الموافقة والبدء --}}
                    <div class="start-form-box mt-5 p-4 rounded-4 text-center border-dashed border-primary">
                        <h5 class="fw-bold text-dark">جاهز للبدء في تقديم الطلب؟</h5>
                        <p class="text-muted small">يقر مقدم الطلب باطلاعه على الشروط والأحكام الفنية السابقة وصحة البيانات
                            التي سيدلي بها.</p>

                        <form action="{{ route('gis.apply.start', $service->slug) }}" method="GET">
                            <div class="form-check d-inline-flex gap-2 mb-3">
                                <input class="form-check-input" type="checkbox" id="agreeCheck" required>
                                <label class="form-check-label small fw-bold" for="agreeCheck">أوافق على كافة الشروط
                                    والأحكام </label>
                            </div>
                            <br>
                            <button type="submit" class="btn btn-primary-gis px-5 py-3 rounded-pill fw-bold h4">انتقل لصفحة
                                التسجيل <i class="fas fa-chevron-left me-2"></i></button>
                        </form>
                    </div>

                </div>

                {{-- العمود الأيسر: معلومات التواصل والتحقق --}}
                <div class="col-lg-4">
                    <aside class="sidebar-sticky">
                        <div class="status-verify-widget shadow-sm p-4 text-center mb-4">
                            <i class="fas fa-search-location text-gold fa-3x mb-3"></i>
                            <h5>تتبع حالة طلبك</h5>
                            <p class="small text-muted">هل قمت بتقديم طلب سابق؟ أدخل كود المعاملة فوراً</p>
                            <form action="{{ route('gis.tracking') }}" method="GET">
                                <input type="text" name="ticket" class="form-control mb-3 text-center"
                                    placeholder="كود الشهادة / الطلب">
                                <button class="btn btn-navy w-100 rounded-pill">تتبع الطلب</button>
                            </form>
                        </div>

                        {{-- <div class="help-center p-4 bg-white rounded-4 border-left-gold shadow-sm">
                            <h6 class="fw-bold mb-3"><i class="fas fa-headset ms-2"></i> الدعم الفني للـ GIS</h6>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2"><i class="fas fa-phone-alt me-2 text-gold"></i> الخط الساخن: 114</li>
                                <li><i class="fas fa-envelope me-2 text-gold"></i> help@gis.kfs.gov.eg</li>
                            </ul>
                        </div> --}}
                    </aside>
                </div>

            </div>
        </div>
    </main>
@endsection
