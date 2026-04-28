@extends('layouts.app')

@section('title', 'آخر الأخبار')
@push('css')
    <link rel="stylesheet" href="{{ asset('css') }}/posts.css">
    <style>
        .post-card .card-title {
            text-align: right;
            padding: 0;
        }
    </style>
@endpush
@section('content')
    <header class="page-header" style="background-image: url('{{ asset('images/bg/news.jpg') }}');">
        <div class="container text-center">
            <h1>آخر الأخبار والمقالات</h1>
            <p>تابع آخر المستجدات والفعاليات والأخبار الرسمية لمحافظة كفر الشيخ</p>
        </div>
    </header>
    <main class="main-content bg-light">
        <div class="container py-5">
            @if ($posts->isNotEmpty())
                <div class="row g-4">
                    @foreach ($posts as $post)
                        <div class="col-lg-4 col-md-6">
                            <div class="post-card">
                                <a href="{{ route('posts.show', $post->slug) }}" class="card-image-link">
                                    <div class="card-image">
                                        <img src="{{ Storage::url($post->thumbnail) }}" alt="{{ $post->title }}">
                                    </div>
                                </a>
                                <div class="card-content">
                                    <a href="#" class="card-category">{{ $post->category->name ?? 'غير مصنف' }}</a>
                                    <h3 class="card-title">
                                        <a href="{{ route('posts.show', $post->slug) }}">{{ $post->title }}</a>
                                    </h3>
                                    <p class="card-excerpt">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($post->content), 100) }}
                                    </p>
                                    <div class="card-meta">
                                        <span class="meta-date">{{ $post->published_at->translatedFormat('d F, Y') }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination Links --}}
                <div class="pagination-wrapper mt-5">
                    {{ $posts->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <p class="h5">عذرًا، لا توجد أخبار لعرضها حاليًا.</p>
                </div>
            @endif

        </div>
    </main>
@endsection
