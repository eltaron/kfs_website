@extends('layouts.app')
@push('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush
@section('content')
    <!-- ======================= Main Content Start ======================= -->
    <main class="main-content">
        @if (session('success'))
            <div class="alert alert-success fs-5 text-center my-4">{{ session('success') }}</div>
        @endif
        {{-- ======================= HERO SECTION ======================= --}}
        @if ($heroSlides->isNotEmpty())
            <section class="premium-hero">
                <div id="heroCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        @foreach ($heroSlides as $slide)
                            <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                {{-- الطبقة السوداء المتدرجة فوق الفيديو لضمان وضوح النص --}}
                                <div class="hero-overlay-gradient"></div>

                                @if ($slide->media_type == 'video')
                                    <div class="video-wrapper">
                                        <video class="hero-media v-content" autoplay muted loop playsinline
                                            poster="{{ asset('images/video_placeholder.png') }}">
                                            <source src="{{ Storage::url($slide->media_path) }}" type="video/mp4">
                                        </video>
                                    </div>
                                @else
                                    <img src="{{ Storage::url($slide->media_path) }}" class="hero-media"
                                        alt="{{ $slide->title }}">
                                @endif

                                <div class="hero-content">
                                    <div class="container text-center text-md-end">
                                        <div class="badge-accent mb-3">{{ !empty($slide->description) ? 'رؤية وطنية' : '' }}
                                        </div>
                                        <h1 class="animate-text-up">{{ $slide->title }}</h1>
                                        @if ($slide->description)
                                            <p class="animate-text-up delay-1">
                                                {{ \Illuminate\Support\Str::limit($slide->description, 130) }}</p>
                                        @endif

                                        @if ($slide->link_url)
                                            <div class="btn-group-animate delay-2">
                                                <a href="{{ $slide->link_url }}" class="btn-modern-gold">
                                                    <span>{{ $slide->link_text ?? 'استكشف الآن' }}</span>
                                                    <i class="fas fa-chevron-left"></i>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- مؤشرات حديثة (Slim Lines) --}}
                    @if ($heroSlides->count() > 1)
                        <div class="modern-indicators">
                            @foreach ($heroSlides as $slide)
                                <button type="button" data-bs-target="#heroCarousel"
                                    data-bs-slide-to="{{ $loop->index }}"
                                    class="{{ $loop->first ? 'active' : '' }}"></button>
                            @endforeach
                        </div>
                    @endif

                    {{-- سهم تمرير للأسفل (تفاعل عصري) --}}
                    <a class="scroll-indicator d-none d-md-flex" href="#events-section" aria-label="انتقل إلى القسم التالي">
                        <span class="mouse-icon"><span class="wheel"></span></span>
                    </a>
                </div>
            </section>
        @endif
        {{-- ======================= Secondary Hero / Values Section ======================= --}}
        @if (!empty($settings['secondary_hero_title']))
            <section class="values-section" id="values-section">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="values-content">
                                <h2 class="values-title">{!! $settings['secondary_hero_title'] !!}</h2>
                                <p class="values-description">{!! $settings['secondary_hero_description'] !!}</p>
                                <a href="{{ $settings['secondary_hero_button_link'] ?? '#' }}"
                                    class="btn btn-primary btn-lg btn-cta-values">
                                    {!! $settings['secondary_hero_button_text'] ?? 'قراءة المزيد' !!}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
        {{-- ======================= Events Section ======================= --}}
        @if ($events->isNotEmpty())
            <section class="events-section" id="events-section">
                <div class="container">
                    <div class="section-header">
                        <div class="title-line"></div>
                        <div class="title-container">
                            <h2>{!! $settings['events_title'] ?? 'أهم الأحداث' !!}</h2>
                            <p>{!! $settings['events_subtitle'] ?? 'تابع آخر الفعاليات والقرارات الرسمية.' !!}</p>
                        </div>
                    </div>
                    <div class="swiper events-slider">
                        <div class="swiper-wrapper">
                            @foreach ($events as $event)
                                <div class="swiper-slide">
                                    <div class="event-card"
                                        onclick="window.location.href=`{{ route('posts.show', $event->slug) ?? '#' }}`">
                                        <div class="card-img"><img src="{{ Storage::url($event->thumbnail) }}"
                                                alt="{{ $event->title }}" /></div>
                                        <div class="card-body">
                                            <h5><a
                                                    href="{{ route('posts.show', $event->slug) ?? '#' }}">{{ $event->title }}</a>
                                            </h5>
                                            <div class="card-meta">
                                                <i class="fas fa-calendar-alt"></i>
                                                <span>{{ $event->published_at->translatedFormat('d F, Y') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="slider-controls">
                        {{-- <div class="progress-line"></div> --}}
                        <div class="slider-navigation">
                            <button class="swiper-button-prev"><i class="fas fa-chevron-right"></i></button>
                            <button class="swiper-button-next"><i class="fas fa-chevron-left"></i></button>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- ======================= News Section (ENHANCED) ======================= --}}
        @if ($featuredNews || $latestNews->isNotEmpty())
            <section class="news-section">
                <div class="container">
                    {{-- Section Header (remains the same) --}}
                    <div class="news-section-header">
                        <div class="title-wrapper">
                            <div class="title-line"></div>
                            <div class="title-container">
                                <h2>{!! $settings['news_title'] ?? 'آخر الأخبار' !!}</h2>
                                <p>{!! $settings['news_subtitle'] ?? 'تحديثات يومية لأهم أخبار المحافظة.' !!}</p>
                            </div>
                        </div>
                        <a href="{{ route('posts.index') ?? '#' }}" class="view-all-link">{!! $settings['news_view_all_link_text'] ?? 'عرض كل الأخبار' !!} <i
                                class="fas fa-arrow-left"></i></a>
                    </div>

                    <div class="row g-4">
                        {{-- Featured News Column --}}
                        @if ($featuredNews)
                            <div class="col-lg-6">
                                <div class="news-card-large-wrapper">
                                    <a href="{{ route('posts.show', $featuredNews->slug) }}"
                                        class="news-card news-card-large">
                                        <img src="{{ Storage::url($featuredNews->thumbnail) }}"
                                            alt="{{ $featuredNews->title }}" />
                                        <div class="news-caption">
                                            <span
                                                class="card-category">{{ $featuredNews->category->name ?? 'أخبار' }}</span>
                                            {{-- Limit the title to 60 characters --}}
                                            <h3>{{ \Illuminate\Support\Str::limit($featuredNews->title, 60) }}</h3>
                                            <div class="card-meta">
                                                <span>{{ $featuredNews->published_at->diffForHumans() }}</span>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        @endif

                        {{-- Latest News Column --}}
                        @if ($latestNews->isNotEmpty())
                            {{-- Make it full width if there's no featured news --}}
                            <div class="col-lg-{{ $featuredNews ? '6' : '12' }}">
                                <div class="news-grid-small">
                                    @foreach ($latestNews as $news)
                                        <a href="{{ route('posts.show', $news->slug) }}" class="news-card news-card-small">
                                            <img src="{{ Storage::url($news->thumbnail) }}" alt="{{ $news->title }}" />
                                            <div class="news-caption">
                                                <div class="caption-content">
                                                    <span
                                                        class="card-category">{{ $news->category->name ?? 'أخبار' }}</span>
                                                    {{-- Limit the title to 45 characters --}}
                                                    <h5>{{ \Illuminate\Support\Str::limit($news->title, 45) }}</h5>
                                                </div>
                                                <div class="card-meta">
                                                    <span>{{ $news->published_at->diffForHumans() }}</span>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </section>
        @endif
        {{-- ======================= Services Section ======================= --}}
        @if ($services->isNotEmpty())
            <section class="services-section">
                <div class="container">
                    <div class="services-section-header">
                        <div class="title-wrapper">
                            <div class="title-line"></div>
                            <div class="title-container">
                                <h2>{!! $settings['services_title'] ?? 'خدمات المحافظة' !!}</h2>
                                <p>{!! $settings['services_subtitle'] ?? 'مجموعة من الخدمات لدعم احتياجاتكم.' !!}</p>
                            </div>
                        </div>
                        {{-- This link now goes to the all-services page --}}
                        <a href="{{ route('services.index') }}" class="view-all-link">{!! $settings['services_view_all_link_text'] ?? 'عرض جميع الخدمات' !!}</a>
                    </div>
                    <div class="row g-4 justify-content-center">
                        @foreach ($services as $service)
                            <div class="col-lg-4 col-md-6">
                                <a href="{{ $service->link ? $service->link : route('services.show', $service) }}"
                                    {{ $service->link ? 'target="_blank"' : '' }}
                                    class="service-card {{ $service->is_highlighted ? 'service-card-highlight' : '' }}">
                                    <div class="card-icon"><i class="{{ $service->icon }}"></i></div>
                                    @if ($service->title_line_1 || $service->title_line_2)
                                        <h3 class="card-title">{!! $service->title_line_1 !!}<br />{!! $service->title_line_2 !!}</h3>
                                    @else
                                        <h3 class="card-title">{!! $service->title !!}</h3>
                                    @endif
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif
        {{-- ======================= Tourism Section ======================= --}}
        @if ($landmarks->isNotEmpty())
            <section class="tourism-section">
                <div class="container">

                    {{-- Section Header --}}
                    <div class="tourism-section-header">
                        <div class="title-wrapper">
                            <div class="title-line"></div>
                            <div class="title-container">
                                <h2>{!! $settings['tourism_title'] ?? 'أهم <span class="highlight">المعالم السياحية</span>' !!}</h2>
                                <p>{!! $settings['tourism_subtitle'] ?? 'اكتشف أبرز المواقع التاريخية والطبيعية.' !!}</p>
                            </div>
                        </div>
                        <a href="{{ route('landmarks.index') }}" class="view-all-link">{!! $settings['tourism_view_all_link_text'] ?? 'استكشف المعالم' !!}</a>
                    </div>

                    {{-- Tourism Slider Container --}}
                    <div class="tourism-slider-container">
                        <div class="swiper tourism-slider">
                            <div class="swiper-wrapper">
                                @foreach ($landmarks as $landmark)
                                    <div class="swiper-slide">
                                        <a href="{{ route('landmarks.show', $landmark) }}" class="landmark-card">
                                            <img src="{{ Storage::url($landmark->thumbnail) }}"
                                                alt="{{ $landmark->name }}" />
                                            <div class="landmark-caption">
                                                <h3>{{ $landmark->name }}</h3>
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        {{-- External Navigation Buttons (Slider Controls) --}}
                        <div class="showcase-nav">
                            <button class="showcase-nav-button swiper-button-prev-custom">
                                <i class="fas fa-chevron-left"></i>
                            </button>
                            <button class="showcase-nav-button swiper-button-next-custom">
                                <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>

                    {{-- Tagline Image --}}
                    @if (!empty($settings['tourism_tagline_image']))
                        <div class="tagline w-50 m-auto">
                            <img src="{{ Storage::url($settings['tourism_tagline_image']) }}"
                                alt="كل مكان ليه حكاية مختلفة" class="tagline-image w-75 " />
                        </div>
                    @endif
                </div>
            </section>
        @endif

        {{-- ======================= Investment Section ======================= --}}
        @if ($investments->isNotEmpty())
            <section class="investment-section pb-0">
                <div class="container">
                    <div class="investment-section-header">
                        <div class="title-wrapper">
                            <div class="title-line"></div>
                            <div class="title-container">
                                <h2>{!! $settings['investment_title'] ?? 'الاستثمار في المحافظة' !!}</h2>
                                <p>{!! $settings['investment_subtitle'] ?? 'اكتشف أبرز الفرص الاستثمارية.' !!}</p>
                            </div>
                        </div>

                        {{-- This link now points to the investments index page --}}
                        <a href="{{ route('investments.index') }}" class="view-all-link">
                            {!! $settings['investment_view_all_link_text'] ?? 'عرض الفرص الاستثمارية' !!}
                        </a>
                    </div>

                    <div class="row g-4 justify-content-center">
                        @foreach ($investments as $investment)
                            <div class="col-lg-6">
                                {{-- This link now points to the investment's detail page --}}
                                <a href="{{ route('investments.show', $investment) }}" class="investment-card">
                                    <div class="card-image">
                                        <img src="{{ Storage::url($investment->thumbnail) }}"
                                            alt="{{ $investment->title }}" />
                                    </div>
                                    <div class="card-content">
                                        <h3>{{ $investment->title }}</h3>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- ======================= Projects Section ======================= --}}
        @if ($nationalProjects->isNotEmpty() || $investmentProjects->isNotEmpty())
            <section class="projects-split-section pt-0">
                <div class="container">
                    <div class="row g-5">
                        {{-- National & Hayah Karima Projects --}}
                        <div class="col-lg-12">
                            <div class="project-category-wrapper">
                                <div class="investment-section-header">
                                    <div class="title-wrapper">
                                        <div class="title-line"></div>
                                        <div class="title-container">
                                            <h2>المشروعات القومية </h2>
                                        </div>
                                    </div>

                                    <a href="{{ route('projects.index') }}" class="view-all-link">
                                        عرض كل المشروعات
                                    </a>
                                </div>
                                <div class="row g-4">
                                    @foreach ($nationalProjects as $project)
                                        <div class="col-md-6 col-lg-4">
                                            <a href="{{ route('projects.show', $project) }}" class="project-split-card">
                                                <div class="card-image"><img
                                                        src="{{ Storage::url($project->thumbnail) }}"
                                                        alt="{{ $project->name }}"></div>
                                                <div class="card-title">{{ $project->name }}</div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
        @endif

        {{-- ======================= New Strategic Investment Plan CTA ======================= --}}
        <section class="investment-plan-cta pb-0">
            <div class="container">
                <div class="plan-cta-card">
                    <div class="row g-0 align-items-stretch">
                        <div class="col-lg-7">
                            <div class="plan-cta-content">
                                <div class="section-badge">خارطة التنمية</div>
                                <h2 class="title">مشروعات الخطة الاستثمارية لمحافظة كفر الشيخ</h2>
                                <p class="description">
                                    اطلع على الخطة الأستثمارية السنوية للمحافظة، والتقارير الموثقة للعام الحالي والأعوام
                                    السابقة. نحن نرسم ملامح المستقبل من خلال رؤية وطنية تهدف إلى تحسين البنية التحتية
                                    والخدمات وتوطين التنمية المستدامة في كافة المراكز.
                                </p>
                                <div class="cta-actions">
                                    <a href="{{ route('investment.plans.index') }}" class="btn btn-gold-lg">
                                        <span>استكشاف الخطة الكاملة</span>
                                        <i class="fas fa-arrow-left"></i>
                                    </a>

                                </div>
                            </div>
                        </div>
                        <div class="col-lg-5 d-none d-lg-block">
                            <div class="plan-cta-image"
                                style="background-image: url('{{ asset('images/investment.png') }}');">
                                <div class="image-overlay-logo">
                                    <img src="{{ asset('images/egypt-vision.png') }}" alt="رؤية مصر 2030">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        {{-- ======================= Achievements Section ======================= --}}
        @if ($achievements->isNotEmpty())
            <section class="achievements-section">
                <div class="container">
                    <div class="row align-items-center g-5">
                        <div class="col-lg-6 order-lg-2">
                            <div class="achievement-content">
                                <div class="achievements-section-header">
                                    <div class="title-line"></div>
                                    <div class="title-container">
                                        <h2>{!! $settings['achievements_title'] ?? 'إنجازات الدولة' !!}</h2>
                                    </div>
                                </div>
                                <p class="description">{!! $settings['achievements_description'] ?? '...' !!}</p>
                                <ul class="achievements-list">
                                    @foreach ($achievements as $achievement)
                                        <li>{{ $achievement->title }}</li>
                                    @endforeach
                                </ul>
                                <a href="#" class="btn-explore">
                                    {!! $settings['achievements_button_text'] ?? 'استكشف الإنجازات' !!}
                                    <span class="btn-icon"><i class="fas fa-arrow-left"></i></span>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-6 order-lg-1">
                            <div class="achievement-video-card">
                                @if ($mainAchievementVideo)
                                    {{-- Assuming mainAchievementVideo is the first featured news for simplicity --}}
                                    <img src="{{ Storage::url($featuredNews->thumbnail ?? 'images/image1.png') }}"
                                        alt="صورة إنجازات المحافظة" />
                                    <a href="{{ $mainAchievementVideo->value ?? '#' }}" target="_blank"
                                        class="play-button" aria-label="تشغيل الفيديو"><i class="fas fa-play"></i></a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

        {{-- ======================= Partner Apps Section ======================= --}}
        @if ($partners->isNotEmpty())
            <section class="tabs-section">
                <div class="container" bis_skin_checked="1">
                    <div class="section-header" bis_skin_checked="1">
                        <div class="title-line" bis_skin_checked="1"></div>
                        <div class="title-container" bis_skin_checked="1">
                            <h2>{!! $settings['apps_title'] ?? 'مواقع تهمك' !!}</h2>
                            <p>{!! $settings['apps_subtitle'] ?? '' !!}</p>
                        </div>
                    </div>
                    <div class="tabs-content-area" bis_skin_checked="1">
                        <div class="tabs-navigation" bis_skin_checked="1">
                            @foreach ($partners as $partner)
                                <a href="#{{ $partner->name }}" class="tab-item {{ $loop->first ? 'active' : '' }}"
                                    data-tab="{{ $partner->name }}"> {{ $partner->name }}</a>
                            @endforeach
                        </div>

                        <div class="tabs-details-view" bis_skin_checked="1">
                            @foreach ($partners as $partner)
                                <div id="{{ $partner->name }}-content"
                                    class="tab-detail {{ $loop->first ? 'active-detail' : '' }}" bis_skin_checked="1">
                                    <img src="{{ Storage::url($partner->image) }}" alt="{{ $partner->name }}"
                                        class="detail-image">
                                    <div class="detail-text" bis_skin_checked="1">
                                        <h3 class="detail-title">{{ $partner->name }}</h3>
                                        <p class="detail-description">
                                            {{ $partner->description }}
                                        </p>
                                        <a href="{{ $partner->link }}" target="_blank" class="cta-button">زيارة الموقع
                                            الإلكتروني</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif


        {{-- ======================= Statistics Section ======================= --}}
        @if ($statistics->isNotEmpty())
            <section class="stats-section">
                <div class="container">
                    <div class="title-wrapper" bis_skin_checked="1">
                        <div class="title-line" bis_skin_checked="1"></div>
                        <div class="title-container" bis_skin_checked="1">
                            <h2>{!! $settings['stats_title'] ?? 'المحافظة في أرقام' !!}</h2>
                            <p>{!! $settings['stats_subtitle'] ?? 'نظرة سريعة على أبرز الإحصائيات.' !!}</p>
                        </div>
                    </div>
                    <div class="row g-4 justify-content-center">
                        @foreach ($statistics as $stat)
                            <div class="col-lg-4 col-md-6">
                                <div class="stat-card">
                                    <i class="{{ $stat->icon_class }} stat-icon-bg"></i>
                                    <h3 class="stat-number" data-target="{{ $stat->number }}">0</h3>
                                    <p class="stat-description">{{ $stat->title }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- ======================= City Guide Section ======================= --}}
        @if ($guideCategories->isNotEmpty())
            <section class="city-guide-section">
                <div class="container-fluid g-0">
                    <div class="row g-0">
                        <div class="col-lg-5">
                            <div class="guide-content">
                                <div class="guide-section-header">
                                    <div class="title-line"></div>
                                    <div class="title-container">
                                        <h2>{!! $settings['city_guide_title'] ?? 'دليل العاصمة' !!}</h2>
                                    </div>
                                </div>
                                <div class="guide-categories">
                                    {{-- Add a "Show All" button --}}
                                    <a href="#" class="category-item active" data-category-id="all">
                                        <span class="category-icon"><i class="fas fa-map-marked-alt"></i></span>
                                        <span class="category-text">عرض الكل</span>
                                    </a>
                                    @foreach ($guideCategories as $category)
                                        <a href="#" class="category-item" data-category-id="{{ $category->id }}">
                                            <span class="category-icon"><i
                                                    class="{{ $category->icon_class }}"></i></span>
                                            <span class="category-text">{{ $category->locations->count() }}
                                                {{ $category->name }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-7">
                            {{-- The Map container --}}
                            <div id="interactiveMap" class="guide-map"></div>
                        </div>
                    </div>
                </div>
            </section>
        @endif

    </main>
    <!-- ======================= Main Content End ======================= -->
@endsection
@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('interactiveMap')) {

                // 1. Prepare data from PHP
                const locations = @json($allLocations);

                // 2. Initialize the map
                const map = L.map('interactiveMap').setView([31.1143, 30.9416], 13); // Centered on Kafr El-Sheikh

                // 3. Add the map tiles (the visual layer)
                L.tileLayer('https://{s}.basemaps.cartocdn.com/dark_all/{z}/{x}/{y}{r}.png', {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
                    subdomains: 'abcd',
                    maxZoom: 20
                }).addTo(map);

                // 4. Create markers and store them
                const markers = {};
                locations.forEach(location => {
                    const icon = L.divIcon({
                        className: 'custom-leaflet-icon',
                        html: `<i class="${location.city_guide_category.icon_class}"></i>`,
                        iconSize: [28, 28],
                    });

                    const marker = L.marker([location.latitude, location.longitude], {
                            icon: icon
                        })
                        .addTo(map)
                        .bindPopup(
                            `<div class="popup-title">${location.name}</div>
                         <a class="popup-link" href="https://maps.google.com/?q=${location.latitude},${location.longitude}" target="_blank">
                             عرض على جوجل ماب <i class="fas fa-external-link-alt"></i>
                         </a>`
                        );

                    if (!markers[location.city_guide_category_id]) {
                        markers[location.city_guide_category_id] = [];
                    }
                    markers[location.city_guide_category_id].push(marker);
                });

                // 5. Handle category filter clicks
                document.querySelectorAll('.guide-categories .category-item').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();

                        document.querySelectorAll('.guide-categories .category-item').forEach(btn =>
                            btn.classList.remove('active'));
                        this.classList.add('active');

                        const categoryId = this.dataset.categoryId;

                        // Hide all markers
                        Object.values(markers).flat().forEach(marker => map.removeLayer(marker));

                        // Show only markers for the selected category
                        if (categoryId === 'all') {
                            Object.values(markers).flat().forEach(marker => marker.addTo(map));
                        } else {
                            if (markers[categoryId]) {
                                markers[categoryId].forEach(marker => marker.addTo(map));
                            }
                        }
                    });
                });
            }
        });
    </script>
@endpush
