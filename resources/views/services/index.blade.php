@extends('layouts.app')

@section('title', 'خدمات المحافظة')
@push('css')
    <link rel="stylesheet" href="{{ asset('css') }}/services.css">
@endpush
@section('content')
    <main class="main-content">
        {{-- Page Header --}}
        <header class="page-header" style="background-image: url('{{ asset('images/bg/services.jpg') }}');">
            <div class="container">
                <h1>جميع الخدمات</h1>
                <p>مجموعة شاملة من الخدمات الموجهة لدعم مواطني المحافظة.</p>
            </div>
        </header>

        <section class="services-section">
            <div class="container">
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
                                <p class="{{ $service->is_highlighted ? 'text-light' : 'text-muted' }}">
                                    {{-- {!! \Illuminate\Support\Str::limit($service->description, 80) !!} --}}
                                </p>
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    </main>
@endsection
