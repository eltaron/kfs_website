@extends('layouts.app')
@section('title', $landmark->name)

@push('css')
    <link rel="stylesheet" href="{{ asset('css') }}/landmarks.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.css" />
    <style>
        :root {
            --primary-color: #c9a226;
            --primary-dark: #b8941f;
            --secondary-color: #1a1a1a;
            --accent-color: #f4f4f4;
        }

        /* معرض الصور */
        .gallery-section {
            margin: 3rem 0;
        }

        .section-title {
            color: var(--primary-color);
            font-weight: 700;
            margin-bottom: 2rem;
            position: relative;
            padding-bottom: 15px;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: 0;
            right: 0;
            width: 60px;
            height: 3px;
            background: var(--primary-color);
        }

        .project-gallery-slider {
            border-radius: 12px;
            overflow: hidden;
        }

        .project-gallery-slider .swiper-slide {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .project-gallery-slider img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            transition: transform 0.3s;
        }

        .project-gallery-slider img:hover {
            transform: scale(1.05);
        }

        /* كيفية الوصول */
        .directions-card {
            background: #fff;
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: 0 2px 20px rgba(0,0,0,0.08);
            margin: 3rem 0;
        }

        .directions-card h4 {
            color: var(--secondary-color);
            font-weight: 700;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 1.4rem;
        }

        .directions-card h4 svg {
            color: var(--primary-color);
        }

        .info-box {
            background: var(--accent-color);
            border-radius: 10px;
            padding: 1.5rem;
            margin: 1.5rem 0;
            border-right: 4px solid var(--primary-color);
        }

        .info-box strong {
            color: var(--primary-color);
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 600;
        }

        .info-box p {
            margin: 0;
            color: #555;
            line-height: 1.8;
        }

        .map-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: var(--primary-color);
            color: #fff;
            padding: 12px 28px;
            border-radius: 8px;
            text-decoration: none;
            transition: all 0.3s;
            margin: 8px;
            font-weight: 600;
            border: 2px solid var(--primary-color);
        }

        .map-btn:hover {
            background: var(--primary-dark);
            border-color: var(--primary-dark);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(201, 162, 38, 0.3);
        }

        .map-btn.secondary {
            background: transparent;
            color: var(--primary-color);
        }

        .map-btn.secondary:hover {
            background: var(--primary-color);
            color: #fff;
        }

        .map-container {
            margin-top: 2rem;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .map-container iframe {
            width: 100%;
            height: 400px;
            border: none;
        }

        #directionsMapContainer {
            margin-top: 2rem;
        }

        #directionsMapLeaflet {
            height: 500px;
            border-radius: 12px;
            z-index: 1;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        /* نموذج الحجز */
        .booking-section {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
            border-radius: 20px;
            padding: 3.5rem 2.5rem;
            margin: 3rem 0;
            border: 2px solid var(--primary-color);
            position: relative;
            overflow: hidden;
        }

        .booking-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--primary-color), var(--primary-dark));
        }

        .booking-header {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .booking-header h3 {
            color: var(--secondary-color);
            font-weight: 700;
            font-size: 1.8rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }

        .booking-header h3 svg {
            color: var(--primary-color);
        }

        .booking-header p {
            color: #666;
            margin: 0;
            font-size: 1rem;
        }

        .booking-form .form-label {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 0.5rem;
        }

        .booking-form .form-control,
        .booking-form .form-select {
            border-radius: 8px;
            padding: 14px 16px;
            border: 2px solid #e0e0e0;
            transition: all 0.3s;
            font-size: 0.95rem;
        }

        .booking-form .form-control:focus,
        .booking-form .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.2rem rgba(201, 162, 38, 0.15);
        }

        .booking-form .form-control::placeholder {
            color: #999;
        }

        .btn-submit-booking {
            background: var(--primary-color);
            color: #fff;
            border: none;
            padding: 16px 40px;
            border-radius: 8px;
            font-weight: 700;
            font-size: 1.1rem;
            width: 100%;
            transition: all 0.3s;
            cursor: pointer;
            margin-top: 1rem;
        }

        .btn-submit-booking:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(201, 162, 38, 0.3);
        }

        .btn-submit-booking:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }

        .booking-note {
            background: #fff3cd;
            border-right: 4px solid var(--primary-color);
            padding: 1rem 1.5rem;
            border-radius: 8px;
            margin: 1.5rem 0;
            color: #856404;
            font-weight: 600;
        }

        .booking-note svg {
            vertical-align: middle;
            margin-left: 8px;
        }

        /* Virtual Tour Section */
        .virtual-tour-section {
            margin: 3rem 0;
        }

        .map-iframe-wrapper-fullwidth {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }

        .map-iframe-wrapper-fullwidth iframe {
            width: 100%;
            height: 450px;
            border: none;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .directions-card,
            .booking-section {
                padding: 2rem 1.5rem;
            }

            .booking-header h3 {
                font-size: 1.4rem;
            }

            .map-btn {
                display: flex;
                margin: 8px 0;
                justify-content: center;
            }

            #directionsMapLeaflet {
                height: 400px;
            }
        }
    </style>
@endpush

@section('content')
    <main class="main-content">
        {{-- Header --}}
        <header class="page-header with-thumbnail" style="background-image: url('{{ Storage::url($landmark->thumbnail) }}');">
            <div class="header-overlay"></div>
            <div class="container text-center">
                <span class="page-subtitle">معلم سياحي</span>
                <h1>{{ $landmark->name }}</h1>
            </div>
        </header>

        <div class="container py-5">
            {{-- التفاصيل --}}
            <article class="rich-text-content"
                style="background: #fff; padding: 2.5rem; border-radius: 12px; box-shadow: 0 2px 20px rgba(0,0,0,0.08);">
                {!! $landmark->details !!}
            </article>

            {{-- معرض الصور --}}
            @if ($landmark->images->isNotEmpty())
                <div class="gallery-section">
                    <h3 class="section-title">معرض الصور</h3>
                    <div class="swiper project-gallery-slider">
                        <div class="swiper-wrapper">
                            @foreach ($landmark->images as $image)
                                <div class="swiper-slide">
                                    <a href="{{ Storage::url($image->path) }}" data-fslightbox="landmark-gallery">
                                        <img src="{{ Storage::url($image->path) }}"
                                            alt="{{ $image->caption ?? $landmark->name }}">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                        <div class="swiper-button-next"></div>
                        <div class="swiper-button-prev"></div>
                    </div>
                </div>
            @endif

            {{-- كيفية الوصول --}}
            @if($landmark->latitude && $landmark->longitude)
                <div class="directions-card">
                    <h4>
                        <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6zm0-2.5a.5.5 0 1 0 0-1 .5.5 0 0 0 0 1z"/>
                        </svg>
                        كيفية الوصول للمعلم
                    </h4>

                    @if($landmark->address)
                        <div class="info-box">
                            <strong>📍 العنوان:</strong>
                            <p>{{ $landmark->address }}</p>
                        </div>
                    @endif

                    <div class="text-center my-4">
                        <button type="button" 
                                class="map-btn"
                                onclick="showDirectionsMap()">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.654.689-1.782.886-3.112.752-1.234-.124-2.503-.523-3.388-.893V2.828zM7.5 1.916v.688c.588-.069 1.19-.093 1.788-.071.61.023 1.21.105 1.788.246V1.5a.5.5 0 0 1 .5-.5h.5a.5.5 0 0 1 .5.5v1.293c.578.141 1.178.223 1.788.246.598.022 1.2-.002 1.788-.071v-.688c-.588.069-1.19.093-1.788.071a12.5 12.5 0 0 0-1.788-.246V1.5a.5.5 0 0 1 .5-.5h.5a.5.5 0 0 1 .5.5v1.293c.578.141 1.178.223 1.788.246.598.022 1.2-.002 1.788-.071v-.688c-.588.069-1.19.093-1.788.071a12.5 12.5 0 0 0-1.788-.246V1.5a.5.5 0 0 1 .5-.5h.5a.5.5 0 0 1 .5.5v1.293c.578.141 1.178.223 1.788.246.598.022 1.2-.002 1.788-.071zM8 5.993c.588-.069 1.19-.093 1.788-.071.61.023 1.21.105 1.788.246V15a.5.5 0 0 1-.5.5h-.5a.5.5 0 0 1-.5-.5V6.164c-.578.141-1.178.223-1.788.246-.598.022-1.2-.002-1.788-.071V5.993z"/>
                            </svg>
                            اعرض خريطة الطريق من موقعي
                        </button>

                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $landmark->latitude }},{{ $landmark->longitude }}" 
                           target="_blank" 
                           class="map-btn secondary">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zM4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                            احسب المسار من موقعي الحالي
                        </a>
                    </div>

                    {{-- خريطة الاتجاهات --}}
                    <div id="directionsMapContainer" style="display: none;">
                        <div class="alert alert-info" role="alert" id="mapLoading">
                            <div class="d-flex align-items-center justify-content-center">
                                <div class="spinner-border spinner-border-sm me-2" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <span>جاري تحديد موقعك وعرض الطريق...</span>
                            </div>
                        </div>
                        <div id="mapError" style="display: none;"></div>
                        <div id="directionsMapLeaflet" style="display: none;"></div>
                    </div>

                </div>
            @endif


            {{-- نموذج الحجز --}}
            <div class="booking-section">
                <div class="booking-header">
                    <h3>
                        <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h2v-4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v4h2a1 1 0 0 0 1-1V4H1z"/>
                        </svg>
                        احجز رحلتك الآن
                    </h3>
                    <p>املأ النموذج التالي لحجز زيارتك للمعلم</p>
                </div>

                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="vertical-align: middle; margin-left: 8px;">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </svg>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16" style="vertical-align: middle; margin-left: 8px;">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </svg>
                        يرجى تصحيح الأخطاء التالية:
                        <ul class="mb-0 mt-2">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('bookings.store') }}" method="POST" id="bookingForm" class="booking-form">
                    @csrf
                    <input type="hidden" name="landmark_id" value="{{ $landmark->id }}">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_name" class="form-label">الاسم الكامل *</label>
                            <input type="text" 
                                   class="form-control" 
                                   id="customer_name" 
                                   name="customer_name" 
                                   value="{{ old('customer_name') }}"
                                   placeholder="أدخل اسمك الثلاثي"
                                   required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="customer_email" class="form-label">البريد الإلكتروني *</label>
                            <input type="email" 
                                   class="form-control" 
                                   id="customer_email" 
                                   name="customer_email" 
                                   value="{{ old('customer_email') }}"
                                   placeholder="example@email.com"
                                   required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="customer_phone" class="form-label">رقم الهاتف *</label>
                            <input type="tel" 
                                   class="form-control" 
                                   id="customer_phone" 
                                   name="customer_phone" 
                                   value="{{ old('customer_phone') }}"
                                   placeholder="01xxxxxxxxx"
                                   required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="number_of_people" class="form-label">عدد الأشخاص *</label>
                            <input type="number" 
                                   class="form-control" 
                                   id="number_of_people" 
                                   name="number_of_people" 
                                   min="1"
                                   value="{{ old('number_of_people', 1) }}"
                                   required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="visit_date" class="form-label">تاريخ الزيارة المطلوب *</label>
                            <input type="date" 
                                   class="form-control" 
                                   id="visit_date" 
                                   name="visit_date" 
                                   value="{{ old('visit_date') }}"
                                   min="{{ date('Y-m-d') }}"
                                   required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="visit_time" class="form-label">الوقت المفضل</label>
                            <input type="time" 
                                   class="form-control" 
                                   id="visit_time" 
                                   name="visit_time" 
                                   value="{{ old('visit_time') }}">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="special_requests" class="form-label">طلبات خاصة أو ملاحظات</label>
                        <textarea class="form-control" 
                                  id="special_requests" 
                                  name="special_requests" 
                                  rows="3"
                                  placeholder="أي متطلبات خاصة أو أسئلة...">{{ old('special_requests') }}</textarea>
                    </div>

                    <div class="booking-note">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                        </svg>
                        ملاحظة: سيتم تأكيد الحجز عبر البريد الإلكتروني أو الهاتف خلال 24 ساعة
                    </div>

                    <button type="submit" class="btn-submit-booking" id="submitBtn">
                        تأكيد الحجز الآن
                    </button>
                </form>
            </div>
        </div>
    </main>
@endsection
@push('scripts')
    {{-- FSLightbox (رابط مستقر) --}}
    <script src="https://cdn.jsdelivr.net/npm/fslightbox@3.4.1/index.js"></script>

    {{-- Swiper --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    {{-- Leaflet & Routing --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gallery Slider
            const gallerySlider = document.querySelector('.project-gallery-slider');
            if (gallerySlider) {
                new Swiper('.project-gallery-slider', {
                    loop: true,
                    spaceBetween: 20,
                    grabCursor: true,
                    slidesPerView: 1,
                    breakpoints: { 768: { slidesPerView: 2 }, 992: { slidesPerView: 3 } },
                    navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' }
                });
            }

            // FSLightbox Safe Init
            if (typeof refreshFsLightbox === 'function') {
                try { refreshFsLightbox(); } catch(e) {}
            }

            // Date Input Min
            const dateInput = document.getElementById('visit_date');
            if (dateInput) dateInput.min = new Date().toISOString().split('T')[0];

            // Form Submit Loading
            const form = document.getElementById('bookingForm');
            const btn = document.getElementById('submitBtn');
            form?.addEventListener('submit', () => {
                btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>جاري الإرسال...';
                btn.disabled = true;
            });
        });

        function showDirectionsMap() {
            const container = document.getElementById('directionsMapContainer');
            const loading = document.getElementById('mapLoading');
            const errorDiv = document.getElementById('mapError');
            const mapDiv = document.getElementById('directionsMapLeaflet');
            
            container.style.display = 'block';
            loading.style.display = 'block';
            errorDiv.style.display = 'none';
            mapDiv.style.display = 'none';

            if (!navigator.geolocation) {
                showError('المتصفح لا يدعم تحديد الموقع الجغرافي');
                return;
            }

            // تعريف الدوال بشكل صريح لمنع تعارض الـ Wrappers
            const onSuccess = (pos) => {
                const uLat = pos.coords.latitude, uLng = pos.coords.longitude;
                const dLat = {{ $landmark->latitude }}, dLng = {{ $landmark->longitude }};
                
                loading.style.display = 'none';
                mapDiv.style.display = 'block';

                const map = L.map('directionsMapLeaflet').setView([uLat, uLng], 13);
                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', { attribution: '© OpenStreetMap' }).addTo(map);
                L.marker([uLat, uLng]).addTo(map).bindPopup('موقعك الحالي').openPopup();
                L.marker([dLat, dLng]).addTo(map).bindPopup('{{ $landmark->name }}');

                L.Routing.control({
                    waypoints: [L.latLng(uLat, uLng), L.latLng(dLat, dLng)],
                    routeWhileDragging: false,
                    // language: 'ar',
                    showAlternatives: true,
                    addWaypoints: false,
                    draggableWaypoints: false,
                    fitSelectedRoutes: true,
                    show: false,
                    lineOptions: { styles: [{ color: '#c9a226', opacity: 0.8, weight: 5 }] }
                }).addTo(map);
            };

            const onError = (err) => {
                let msg = 'فشل تحديد الموقع: ';
                if (err.code === 1) msg += 'تم رفض إذن الوصول للموقع.';
                else if (err.code === 2) msg += 'الموقع غير متاح حالياً.';
                else if (err.code === 3) msg += 'انتهت مهلة الانتظار.';
                else msg += 'خطأ غير معروف.';
                showError(msg);
            };

            try {
                // نمرر دالة خطأ صريحة حتى لو فاضية، عشان الـ Wrapper ميتكسرش
                navigator.geolocation.getCurrentPosition(onSuccess, onError || function(){}, {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                });
            } catch (e) {
                // Fallback لو الـ Wrapper قفل الـ API خالص
                loading.style.display = 'none';
                errorDiv.innerHTML = `
                    <div class="alert alert-warning">
                        تعذر تفعيل الخريطة التفاعلية بسبب تعارض مع إضافة المتصفح. 
                        يمكنك استخدام <a href="https://www.google.com/maps/dir/?api=1&destination={{ $landmark->latitude }},{{ $landmark->longitude }}" target="_blank" class="fw-bold">رابط خرائط جوجل المباشر</a> للوصول للمعلم.
                    </div>`;
                errorDiv.style.display = 'block';
            }
        }

        function showError(msg) {
            // document.getElementById('mapLoading').style.display = 'none';
            // const errDiv = document.getElementById('mapError');
            // errDiv.innerHTML = `<div class="alert alert-danger">⚠️ ${msg}</div>`;
            // errDiv.style.display = 'block';
        }
    </script>
@endpush