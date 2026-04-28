@extends('layouts.app')

@section('title', $project->name)
@push('css')
    <link rel="stylesheet" href="{{ asset('css') }}/projects.css">
@endpush
@section('content')
    <main class="main-content">
        {{-- Project Header with Thumbnail --}}
        <header class="page-header with-thumbnail" style="background-image: url('{{ Storage::url($project->thumbnail) }}');">
            <div class="header-overlay"></div>
            <div class="container text-center">
                <span class="page-subtitle">مشروعات المحافظة</span>
                <h1>{{ $project->name }}</h1>
            </div>
        </header>

        <div class="container py-5">
            <div class="project-details-wrapper">

                {{-- Project Description --}}
                @if ($project->description)
                    <div class="project-section">
                        <h3 class="section-title">عن المشروع</h3>
                        <div class="rich-text-content">
                            {!! $project->description !!}
                        </div>
                    </div>
                @endif

                <div class="row">
                    {{-- Image Gallery --}}
                    @if ($project->images->isNotEmpty())
                        <div class="col-md-7 project-section">
                            <h3 class="section-title">معرض الصور </h3>
                            <div class="swiper project-gallery-slider">
                                <div class="swiper-wrapper">
                                    @foreach ($project->images as $image)
                                        <div class="swiper-slide">
                                            <a href="{{ Storage::url($image->path) }}" data-fslightbox="project-gallery">
                                                <img src="{{ Storage::url($image->path) }}"
                                                    alt="{{ $image->caption ?? $project->name }}">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                                {{-- Slider Navigation --}}
                                <div class="swiper-button-next"></div>
                                <div class="swiper-button-prev"></div>
                            </div>
                        </div>
                    @endif
                    <aside class="col-md-5 sidebar-sticky">
                        {{-- Contact CTA Widget --}}
                        <div class="cta-widget">
                            <div class="cta-icon">
                                <i class="fas fa-headset"></i>
                            </div>
                            <h3>في حالة الرغبة في التقدم لأي فرصة أو وجود أي استفسارات</h3>
                            <a href="{{ route('investment.contact') }}" class="btn btn-light w-100">تواصل معنا من هنا</a>
                        </div>
                    </aside>
                </div>
                {{-- Embedded Map --}}
                @if ($project->iframe || ($project->latitude && $project->longitude))
                    <div class="project-section">
                        <h3 class="section-title">موقع المشروع على الخريطة</h3>
                        <div class="map-iframe-wrapper-fullwidth">
                            @if ($project->iframe)
                                {{-- If a custom iframe exists, display it --}}
                                {!! preg_replace('/(width|height)="[^"]*"/', '', $project->iframe) !!}
                            @else
                                {{-- Otherwise, display the Google Maps embed using coordinates --}}
                                <iframe width="100%" height="450" style="border:0" loading="lazy" allowfullscreen
                                    src="https://maps.google.com/maps?q={{ $project->latitude }},{{ $project->longitude }}&hl=ar&z=15&amp;output=embed">
                                </iframe>
                            @endif
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    {{-- Library for image lightbox/gallery popup --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fslightbox/3.4.1/fslightbox.bundle.min.js"></script>
    {{-- Swiper.js for the gallery --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (document.querySelector('.project-gallery-slider')) {
                const projectGallery = new Swiper('.project-gallery-slider', {
                    loop: true,
                    spaceBetween: 20,
                    slidesPerView: 1,
                    breakpoints: {
                        768: {
                            slidesPerView: 1,
                        },
                        992: {
                            slidesPerView: 1,
                        }
                    },
                    navigation: {
                        nextEl: '.swiper-button-next',
                        prevEl: '.swiper-button-prev',
                    },
                });
            }
        });
    </script>
@endpush
