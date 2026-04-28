@extends('layouts.app')
@section('title', 'بوابة الخدمات الجيومكانية')

@push('css')
    <link rel="stylesheet" href="{{ asset('css/gis-services.css') }}">
@endpush

@section('content')
    <section class="gis-gateways shadow-inner">

        {{-- هيدر القسم --}}
        <header class="gateway-header text-center animate__animated animate__fadeInDown">
            <div class="container">
                <h1 class="fw-black">منظومة الخدمات المكانية</h1>
                <p class="mb-0 small text-uppercase ls-2">خدمات جغرافية رقمية موثقة وآمنة</p>
            </div>
        </header>

        <div class="container">

            {{-- سكشن مراحل الخدمة --}}
            {{-- <div class="phases-container text-center">
                <h4 class="section-divider">مراحل إتمام الخدمة</h4>
                <div class="timeline-wizard">
                    <div class="step active"><span>1</span>
                        <p>التعريف</p>
                    </div>
                    <div class="step"><span>2</span>
                        <p>ملء البيانات</p>
                    </div>
                    <div class="step"><span>3</span>
                        <p>مقابل اداء الخدمة </p>
                    </div>
                    <div class="step"><span>4</span>
                        <p>المتابعة</p>
                    </div>
                </div>
            </div> --}}

            {{-- سكشن الفيديو الإرشادي --}}
            <div class="video-section-container mb-5 mt-5">
                <h4 class="section-divider">فيديو شرح مراحل الحصول علي الخدمة </h4>
                <div class="video-wrapper mx-auto" style="max-width: 700px;">
                    <iframe width="100%" height="320" src="https://www.youtube.com/embed/WREUvW9NIDM" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
                </div>
            </div>

            {{-- سكشن الخدمات الرئيسية والفرعية --}}
            <div class="services-selection mt-5">

                <div class="row g-5 justify-content-center">
                    @foreach ($categories as $cat)
                        <div class="col-lg-6 col-md-12">
                            <div class="service-cat-card shadow">
                                <div class="cat-head">
                                    <i class="{{ $cat->icon ?? 'fas fa-map-marked-alt' }}"></i>
                                    <h3 class="cat-title">{{ $cat->name }}</h3>
                                    <div class="cat-desc text-muted">{!! $cat->description !!}</div>
                                </div>

                                <div class="sub-services-list">
                                    @foreach ($cat->subServices as $sub)
                                        <a href="{{ route('gis.service.show', $sub->slug) }}" class="sub-link">
                                            <span><i class="fas fa-arrow-left ms-2"></i> {{ $sub->name }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>


        </div>
    </section>
@endsection
