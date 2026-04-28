@extends('layouts.app')

@section('title', 'الاستثمار: ' . $investment->title)

{{-- Push the specific CSS for this page --}}
@push('css')
    <link rel="stylesheet" href="{{ asset('css/investments.css') }}">
    {{-- Leaflet CSS for the map --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
@endpush

@section('content')
    <main class="main-content bg-light">
        {{-- Page Header --}}
        <header class="page-header with-thumbnail"
            style="background-image: url('{{ !empty($investment->thumbnail) ? Storage::url($investment->thumbnail) : asset('images/page-header-bg.jpg') }}');">
            <div class="header-overlay"></div>
            <div class="container text-center">
                @if ($investment->type == 1)
                    <span class="page-subtitle">فرص استثمارية</span>
                @endif
                <h1>{{ $investment->title }}</h1>
            </div>
        </header>

        <div class="container py-5">
            <div class="row g-5">
                {{-- Main Content Column --}}
                <div class="col-lg-{{ $investment->type == 1 ? '7' : '12' }}">
                    <article class="investment-content-wrapper">
                        @if ($investment->type == 1)
                            <h2 class="main-title">مميزات الاستثمار في {{ $investment->title }}</h2>
                        @endif
                        {{-- Rich Text Description --}}
                        @if ($investment->description)
                            <div class="rich-text-content">
                                {!! $investment->description !!}
                            </div>
                        @else
                            <p class="text-muted">لا توجد تفاصيل متاحة لهذه الفرصة الاستثمارية حاليًا.</p>
                        @endif
                    </article>
                </div>
                @if ($investment->type == 1)
                    {{-- Sticky Sidebar Column --}}
                    <div class="col-lg-5">
                        <aside class="sidebar-sticky">
                            {{-- Contact CTA Widget --}}
                            <div class="cta-widget">
                                <div class="cta-icon">
                                    <i class="fas fa-headset"></i>
                                </div>
                                <h3>في حالة الرغبة في التقدم لأي فرصة أو وجود أي استفسارات</h3>
                                <a href="{{ route('investment.contact') }}" class="btn btn-light w-100">تواصل معنا من
                                    هنا</a>
                            </div>
                        </aside>
                    </div>
                @endif
            </div>
            {{-- Interactive Map for Related Projects --}}
            @if ($investment->projects->isNotEmpty())
                <div class="map-interactive-section mt-5">
                    <h2 class="main-title mb-4"><i class="fas fa-map-location-dot me-2"></i>{{ $investment->title }} المتاحة
                        وتوزيعها
                        الجغرافي</h2>

                    <div class="map-dynamic-container">
                        {{-- القائمة الجانبية للخريطة --}}
                        <div class="map-projects-sidebar">
                            <div class="sidebar-header">
                                <span>قائمة {{ $investment->title }}</span>
                                <span class="badge bg-gold">{{ count($investment->projects) }} </span>
                            </div>
                            <div class="projects-list" id="projectsSidebarList">
                                {{-- سيتم تعبئة الفرص عبر الـ Javascript --}}
                            </div>
                        </div>

                        {{-- حاوية الخريطة --}}
                        <div class="map-canvas-wrapper">
                            <div id="investmentProjectsMap" style="height: 100%;"></div>
                        </div>
                    </div>
                </div>
            @endif
            {{-- Full-width Embedded IFrame Map (if it exists) --}}
            @if ($investment->map_iframe)
                <div class="map-section mt-5 pt-4">
                    <h3 class="sub-section-title text-center">موقع الفرصة على الخريطة</h3>
                    <div class="map-iframe-wrapper-fullwidth">
                        {!! preg_replace('/(width|height)="[^"]*"/', '', $investment->map_iframe) !!}
                    </div>
                </div>
            @endif
        </div>
    </main>
    @php
        $projects = $investment->projects
            ->filter(function ($p) {
                return $p->latitude && $p->longitude;
            })
            ->map(function ($p) {
                return [
                    'name' => $p->name,
                    'lat' => $p->latitude,
                    'lng' => $p->longitude,
                    'slug' => $p->slug,
                ];
            })
            ->values();
    @endphp

@endsection
@push('scripts')
    {{-- Leaflet JS & CSS --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mapElement = document.getElementById('investmentProjectsMap');
            const sidebarList = document.getElementById('projectsSidebarList');
            const projects = @json($projects);

            if (mapElement && projects.length > 0) {
                // استخدام Esri Satellite لمظهر أقوى وأكثر رسمية (يشبه وزارة الاستثمار)
                const map = L.map(mapElement, {
                    scrollWheelZoom: false
                }).setView([31.1143, 30.9416], 12);

                // طبقة خرائط شوارع وأسماء عربية واضحة
                L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
                        attribution: 'Tiles &copy; Esri'
                    }).addTo(map);

                const markers = {};

                projects.forEach((project, index) => {
                    // 1. إضافة المشروع للقائمة الجانبية (Sidebar)
                    const card = document.createElement('div');
                    card.className = 'project-item-card';
                    card.innerHTML = `
                    <h4><i class="fas fa-location-dot me-1 text-gold"></i> ${project.name}</h4>
                    <span class="view-link">اضغط للتحديد على الخريطة <i class="fas fa-chevron-left fa-xs"></i></span>
                `;
                    sidebarList.appendChild(card);

                    // 2. إضافة الماركر على الخريطة
                    const marker = L.marker([project.lat, project.lng])
                        .addTo(map)
                        .bindPopup(`
                        <div class="p-2">
                            <div class="popup-title">${project.name}</div>
                            <a href="/projects/${project.slug}" class="btn btn-sm btn-dark w-100 mt-2 text-white">تفاصيل المشروع</a>
                        </div>
                    `);

                    markers[index] = marker;

                    // 3. عند الضغط على الكارد في القائمة الجانبية
                    card.addEventListener('click', () => {
                        // تمييز الكارت المختار
                        document.querySelectorAll('.project-item-card').forEach(c => c.classList
                            .remove('active'));
                        card.classList.add('active');

                        // توجيه الخريطة وفتح البوب اب
                        map.flyTo([project.lat, project.lng], 15);
                        marker.openPopup();
                    });
                });

                // ملاءمة حدود الخريطة لتشمل كل النقاط
                const markerGroup = L.featureGroup(Object.values(markers));
                map.fitBounds(markerGroup.getBounds().pad(0.2));

                setTimeout(() => {
                    map.invalidateSize();
                }, 300);
            }
        });
    </script>
@endpush
