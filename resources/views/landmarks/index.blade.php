@extends('layouts.app')
@section('title', 'اكتشف المعالم السياحية')

@push('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="{{ asset('css') }}/landmarks-show.css">
@endpush

@section('content')
    <main class="main-content tourism-bg pb-5">
        {{-- Page Header --}}
        <header class="page-header tourism-header" style="background-image: url('{{ asset('images/bg/terosim.jpg') }}');">
            <div class="container text-center">
                <span class="badge-gold mb-3">كنوز وتاريخ</span>
                <h1>الخريطة السياحية التفاعلية</h1>
                <p class="lead text-white-50">دليلك لاستكشاف المعالم التاريخية والطبيعية في قلب الدلتا</p>
            </div>
        </header>

        <div class="container-fluid py-5 px-lg-5">
            {{-- سكشن الخريطة والسياحة التفاعلي --}}
            <div class="tourism-interactive-grid mb-5">
                {{-- قائمة المعالم السياحية (الجانب الأيمن) --}}
                <div class="map-sidebar-tourism">
                    <div class="sidebar-top shadow-sm">
                        <h5 class="m-0"><i class="fas fa-list-ul me-2"></i> جميع المعالم</h5>
                    </div>
                    <div class="list-container custom-scrollbar" id="landmarksSideList">
                        {{-- يتم تعبئتها بواسطة Javascript --}}
                    </div>
                </div>

                {{-- الخريطة التفاعلية (الجانب الأيسر) --}}
                <div class="map-canvas-tourism">
                    <div id="interactiveTourismMap"></div>
                    <div class="map-loader-overlay">
                        <div class="spinner-grow text-warning"></div>
                    </div>
                </div>
            </div>

            {{-- الأرشيف الشبكي بالأسفل --}}
            <div class="container">
                <h3 class="section-title-premium text-center">ألبوم الذكريات السياحية</h3>
                <div class="row g-4 mt-2 justify-content-center">
                    @foreach ($landmarks as $landmark)
                        <div class="col-lg-4 col-md-4 col-sm-6">
                            <div class="landmark-premium-card shadow-sm">
                                <a href="{{ route('landmarks.show', $landmark->id) }}" class="d-block h-100 w-100">
                                    <img src="{{ Storage::url($landmark->thumbnail) }}" alt="{{ $landmark->name }}">
                                    <div class="card-overlay-modern">
                                        <h5 class="m-0 text-white fw-bold">{{ $landmark->name }}</h5>
                                        <span class="btn-discover">التفاصيل الكاملة <i
                                                class="fas fa-long-arrow-alt-left ms-2"></i></span>
                                    </div>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- الترقيم --}}
                <div class="mt-5 d-flex justify-content-center">
                    {{ $landmarks->links() }}
                </div>
            </div>
        </div>
    </main>

    {{-- تحضير بيانات الخريطة بناءً على التعديلات الجديدة --}}
    @php
        $mapData = $landmarks
            ->filter(fn($l) => $l->latitude && $l->longitude)
            ->map(
                fn($l) => [
                    'id' => $l->id,
                    'name' => $l->name,
                    'lat' => (float) $l->latitude,
                    'lng' => (float) $l->longitude,
                    'thumb' => Storage::url($l->thumbnail),
                ],
            )
            ->values();
    @endphp

@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mapData = @json($mapData);
            const mapContainer = document.getElementById('interactiveTourismMap');
            const listWrapper = document.getElementById('landmarksSideList');

            if (mapContainer && mapData.length > 0) {
                // إعداد الخريطة
                const map = L.map(mapContainer, {
                        scrollWheelZoom: false
                    })
                    .setView([31.1143, 30.9416], 11);

                // طبقة شوارع بالعربي احترافية
                L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/{z}/{y}/{x}', {
                        attribution: '© Esri Tourism Map'
                    }).addTo(map);

                const markers = L.featureGroup();

                mapData.forEach((item) => {
                    // 1. إنشاء عنصر القائمة اليمين
                    const card = document.createElement('div');
                    card.className = 'landmark-side-item';
                    card.innerHTML = `
                        <img src="${item.thumb}" class="item-thumb shadow-sm">
                        <div class="item-info">
                            <h6>${item.name}</h6>
                            <small class="text-muted"><i class="fas fa-location-dot me-1"></i> اضغط للمعاينة</small>
                        </div>
                    `;
                    listWrapper.appendChild(card);

                    // 2. إنشاء الماركر
                    const marker = L.marker([item.lat, item.lng]).addTo(markers);
                    marker.bindPopup(`
                        <div style="min-width: 150px">
                            <img src="${item.thumb}" style="width:100%; border-radius:8px; margin-bottom:5px">
                            <strong style="color:#1e272e">${item.name}</strong>
                            <hr class="my-1">
                            <a href="/landmarks/${item.id}" style="color:#e1b12c; text-decoration:none; font-weight:800; font-size:0.8rem">عرض التفاصيل ←</a>
                        </div>
                    `);

                    // 3. الربط عند الضغط
                    card.addEventListener('click', () => {
                        document.querySelectorAll('.landmark-side-item').forEach(el => el.classList
                            .remove('active'));
                        card.classList.add('active');
                        map.flyTo([item.lat, item.lng], 14, {
                            duration: 1.5
                        });
                        marker.openPopup();
                    });
                });

                markers.addTo(map);
                map.fitBounds(markers.getBounds().pad(0.2));

                // إخفاء الـ Loading بعد جاهزية الخريطة
                setTimeout(() => {
                    document.querySelector('.map-loader-overlay').style.display = 'none';
                    map.invalidateSize();
                }, 400);
            }
        });
    </script>
@endpush
