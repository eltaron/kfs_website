@extends('layouts.app')
@section('title', 'استدامة للتدريب والتطوير')
@push('css')
    <link rel="stylesheet" href="{{ asset('css') }}/estidama.css">
@endpush
@section('content')
    <main class="main-content">

        {{-- 1. Hero Header --}}

        <header class="page-header" style="background-image: url('{{ asset('images/bg/estidama.jpeg') }}');">
            <div class="container text-center">
                @if (!empty($settings['estidama_logo_white']))
                    <img src="{{ Storage::url($settings['estidama_logo_white']) }}" alt="شعار استدامة" class="hero-logo">
                @endif
                <h1 class="hero-title">{!! $settings['estidama_hero_title'] ?? 'استدامة للتدريب والتطوير' !!}</h1>
            </div>
        </header>
        <div class="container py-5">

            {{-- 2. About The Center (New Design) --}}
            <section class="estidama-about-wrapper pt-0 mb-3">
                <div class="container">
                    <div class="row align-items-center g-5">
                        {{-- Video Column --}}
                        <div class="col-lg-6 order-lg-2">
                            <div class="video-wrapper">
                                <iframe width="560" height="315"
                                    src="https://www.youtube.com/embed/qjrUytGahVY?controls=1&amp;rel=0&amp;playsinline=0&amp;modestbranding=0&amp;autoplay=0&amp;enablejsapi=1&amp;origin=https%3A%2F%2Fkfs.gov.eg&amp;widgetid=1&amp;forigin=https%3A%2F%2Fkfs.gov.eg%2Festidama-new%2F&amp;aoriginsup=1&amp;gporigin=https%3A%2F%2Fkfs.gov.eg%2F&amp;vf=1"
                                    title="YouTube
                                    video player" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                    referrerpolicy="strict-origin-when-cross-origin" allowfullscreen></iframe>
                            </div>
                        </div>

                        {{-- Text Column --}}
                        <div class="col-lg-6 order-lg-1">
                            <h2 class="section-title-alt">عن المركز</h2>
                            <div class="features-list">
                                {!! $settings['estidama_infrastructure'] !!}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- 3. Vision, Mission & Goals --}}
            <section class="vision-mission-section">
                <div class="row g-4 justify-content-center">
                    <div class="col-md-4">
                        <div class="info-card">
                            {{-- <img src="{{ asset('images/vision-icon.svg') }}" alt="الرؤية" class="info-card-icon"> --}}
                            <h3>الرؤية</h3>
                            <p>{!! $settings['estidama_vision'] ?? 'لم يتم إضافة محتوى بعد.' !!}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card">
                            {{-- <img src="{{ asset('images/mission-icon.svg') }}" alt="الرسالة" class="info-card-icon"> --}}
                            <h3>الرسالة</h3>
                            <p>{!! $settings['estidama_mission'] ?? 'لم يتم إضافة محتوى بعد.' !!}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="info-card">
                            {{-- <img src="{{ asset('images/goals-icon.svg') }}" alt="الأهداف" class="info-card-icon"> --}}
                            <h3>الأهداف</h3>
                            <div class="goals-list rich-text-content">
                                {!! $settings['estidama_strategic_goals'] ?? 'لم يتم إضافة محتوى بعد.' !!}
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            {{-- 4. Available Programs --}}
            @if ($trainingPrograms->isNotEmpty())
                <section class="project-section" id="programs">
                    <h2 class="section-title text-center">أحدث البرامج التدريبية المتاحة</h2>
                    <div class="row g-4">
                        @foreach ($trainingPrograms as $program)
                            <div class="col-lg-4 col-md-6">
                                <div class="program-card">
                                    <div class="card-image"><img src="{{ Storage::url($program->image) }}"
                                            alt="{{ $program->title }}"></div>
                                    <div class="card-content">
                                        <span class="card-center">{{ $program->trainingCenter->name ?? '' }}</span>
                                        <h4 class="card-title">{{ $program->title }}</h4>
                                        <p class="card-excerpt">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($program->description), 80) }}</p>
                                        <a href="{{ route('estidama.apply', $program) }}"
                                            class="btn btn-primary w-100 mt-auto">التسجيل في
                                            البرنامج</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="text-center mt-4">
                        <a href="{{ route('estidama.programs') }}" class="btn btn-outline-primary px-4">عرض كل البرامج</a>
                    </div>
                </section>
            @endif

            {{-- 5. Statistics Section --}}
            <section class="stats-section-hex">
                <div class="row justify-content-center g-4">
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="stat-hex-card">
                            <span class="stat-number" data-target="715">715</span>
                            <p class="stat-label">عدد البرامج المقدمة</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="stat-hex-card">
                            <span class="stat-number" data-target="11948">11948</span>
                            <p class="stat-label">عدد المتدربين</p>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-4 col-sm-6">
                        <div class="stat-hex-card">
                            <span class="stat-number" data-target="165">165</span>
                            <p class="stat-label">الطاقة الاستيعابية</p>
                        </div>
                    </div>
                </div>
            </section>

            {{-- 6. Events Slider --}}
            @if ($events->isNotEmpty())
                <section class="project-section" id="events">
                    <h2 class="section-title text-center">أهم الأحداث بمركز استدامة</h2>
                    <div class="swiper estidama-events-slider px-3">
                        <div class="swiper-wrapper">
                            @foreach ($events as $event)
                                <div class="swiper-slide">
                                    <div class="estidama-event-card">
                                        <img src="{{ Storage::url($event->image) }}" alt="{{ $event->title }}">
                                        <div class="event-caption">{{ $event->title }}</div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-pagination"></div>
                    </div>
                </section>
            @endif

            {{-- 7. Partners Slider --}}
            {{-- @if ($partners->isNotEmpty())
                <section class="partners-section" id="partners">
                    <h2 class="section-title text-center">الإعتمادات والشركاء الاستراتيجيين</h2>
                    <div class="swiper partners-slider">
                        <div class="swiper-wrapper">
                            @foreach ($partners as $partner)
                                <div class="swiper-slide">
                                    <a href="{{ $partner->link ?? '#' }}" target="_blank" rel="noopener">
                                        <img src="{{ Storage::url($partner->logo) }}" alt="{{ $partner->name }}"
                                            title="{{ $partner->name }}">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
            @endif --}}
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('.estidama-events-slider')) {
                new Swiper('.estidama-events-slider', {
                    loop: true,
                    slidesPerView: 1,
                    spaceBetween: 20,
                    pagination: {
                        el: '.swiper-pagination',
                        clickable: true
                    },
                    breakpoints: {
                        768: {
                            slidesPerView: 2
                        },
                        992: {
                            slidesPerView: 3
                        }
                    }
                });
            }
            if (document.querySelector('.partners-slider')) {
                new Swiper('.partners-slider', {
                    loop: true,
                    autoplay: {
                        delay: 2500,
                        disableOnInteraction: false
                    },
                    slidesPerView: 2,
                    spaceBetween: 30,
                    breakpoints: {
                        576: {
                            slidesPerView: 3
                        },
                        768: {
                            slidesPerView: 4
                        },
                        992: {
                            slidesPerView: 3
                        }
                    }
                });
            }
        });
    </script>
@endpush
