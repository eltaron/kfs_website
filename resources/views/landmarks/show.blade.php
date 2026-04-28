@extends('layouts.app')
@section('title', $landmark->name)
@push('css')
    <link rel="stylesheet" href="{{ asset('css') }}/landmarks.css">
@endpush
@section('content')
    <main class="main-content">
        <header class="page-header with-thumbnail" style="background-image: url('{{ Storage::url($landmark->thumbnail) }}');">
            <div class="header-overlay"></div>
            <div class="container text-center">
                <span class="page-subtitle">معلم سياحي</span>
                <h1>{{ $landmark->name }}</h1>
            </div>
        </header>
        <div class="container py-5">
            <article class="rich-text-content"
                style="background: #fff; padding: 2rem; border-radius: 12px; box-shadow: var(--shadow-soft);">
                {!! $landmark->details !!}
            </article>

            @if ($landmark->images->isNotEmpty())
                <div class="project-section mt-5">
                    <h3 class="section-title">معرض الصور</h3>
                    {{-- Reusing the project gallery slider for landmarks --}}
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

            @if ($landmark->iframe)
                <div class="project-section mt-5">
                    <h3 class="section-title">جولة افتراضية / خريطة تفصيلية</h3>
                    <div class="map-iframe-wrapper-fullwidth">
                        {!! preg_replace('/(width|height)="[^"]*"/', '', $landmark->iframe) !!}
                    </div>
                </div>
            @endif
        </div>
    </main>
@endsection

@push('scripts')
    {{-- 1. Library for image lightbox/gallery popup (FSLightbox) --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.4.1/fslightbox.bundle.min.js"
        integrity="sha512-Vzuz13W09dM2w3ZdeZ+S5r+hQ65I5P76yC/0n4l5L5J2mG2tD1I//3s0uS9bn7N2/XjE/kYpcCen/c3xg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    {{--
  2. Swiper.js library.
  It's better to load this in your main app layout (layouts/app.blade.php)
  since multiple pages use it, but if you want to load it only here, this is how.
--}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    {{-- 3. Custom JavaScript to initialize the gallery slider --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Find the project gallery slider on the page
            const gallerySliderElement = document.querySelector('.project-gallery-slider');

            if (gallerySliderElement) {
                const projectGallery = new Swiper('.project-gallery-slider', {
                    // Configuration options for the slider
                    loop: true,
                    spaceBetween: 20, // Space between slides in pixels
                    grabCursor: true,

                    // How many slides to show based on screen size
                    slidesPerView: 1, // Default for mobile
                    breakpoints: {
                        // when window width is >= 768px
                        768: {
                            slidesPerView: 2,
                        },
                        // when window width is >= 992px
                        992: {
                            slidesPerView: 3,
                        }
                    },

                    // Navigation arrows
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                });
            }

            // Initialize FSLightbox after sliders are ready
            // This will find all `data-fslightbox` attributes on the page
            refreshFsLightbox();
        });
    </script>
@endpush
