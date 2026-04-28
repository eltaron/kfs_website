@extends('layouts.app')
@section('title', $post->title)
@push('css')
    <link rel="stylesheet" href="{{ asset('css/posts-new.css') }}">
@endpush

@section('content')
    {{-- Header القسم --}}
    <header class="post-header text-center mb-4"
        style="background-image: url('{{ asset('images/bg/news.jpg') }}');background-size: cover; background-position: center;">
        <div class="container">
            @if ($post->category)
                <span class="post-category shadow-sm">{{ $post->category->name }}</span>
            @endif
            <h1 class="post-title">{{ $post->title }}</h1>
            <div class="post-meta mt-3">
                <span>بواسطة فريق التحرير</span> | <span><i class="far fa-calendar-alt"></i>
                    {{ $post->published_at->translatedFormat('d F, Y') }}</span>
            </div>
        </div>
    </header>

    <main class="main-content">
        <div class="container pb-5">
            <div class="row g-4">
                {{-- الجزء اليمين: المقال --}}
                <div class="col-lg-8">
                    <div class="post-content-card">
                        <img src="{{ Storage::url($post->thumbnail) }}" class="featured-image" alt="Image">
                        <article class="article-inner">
                            {!! $post->content !!}

                            <div class="tags-section mt-5 pt-3 border-top">
                                <i class="fas fa-tags me-2"></i> الكلمات الدالة:
                                <span class="text-primary">#كفر_الشيخ #المحافظة #أخبار_رسمية</span>
                            </div>
                        </article>
                    </div>
                </div>

                {{-- الجزء الشمال: السايدبار --}}
                <div class="col-lg-4">
                    <aside class="sidebar-sticky">
                        {{-- مشاركة الخبر --}}
                        <div class="sidebar-widget">
                            <h5 class="widget-title">مشاركة الخبر</h5>
                            <div class="share-buttons-grid">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}"
                                    target="_blank" class="share-btn"><i class="fab fa-facebook-f"></i></a>
                                <a href="https://x.com/intent/tweet?url={{ url()->current() }}" target="_blank"
                                    class="share-btn"><i class="fab fa-x-twitter"></i></a>
                                <a href="https://wa.me/?text={{ url()->current() }}" target="_blank" class="share-btn"><i
                                        class="fab fa-whatsapp"></i></a>
                                <a href="mailto:?subject={{ $post->title }}" class="share-btn"><i
                                        class="fas fa-envelope"></i></a>
                            </div>
                        </div>

                        {{-- أخبار صلة --}}
                        @if ($relatedPosts->isNotEmpty())
                            <div class="sidebar-widget">
                                <h5 class="widget-title">أخبار قد تهمك</h5>
                                @foreach ($relatedPosts as $related)
                                    <a href="{{ route('posts.show', $related->slug) }}" class="related-item">
                                        <img src="{{ Storage::url($related->thumbnail) }}">
                                        <h6>{{ Str::limit($related->title, 60) }}</h6>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </aside>
                </div>
            </div>

            {{-- سكشن التعليقات - نسخة أنيقة وواضحة --}}
            @if ($post->allow_comments)
                <div class="comment-form-container mt-5 shadow-lg">
                    <h3 class="text-center mb-4 fw-bold">اترك تعليقك البناء</h3>
                    <form action="{{ route('comments.store', $post) }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6"><input type="text" name="author_name" class="form-control"
                                    placeholder="الاسم" required></div>
                            <div class="col-md-6"><input type="email" name="author_email" class="form-control"
                                    placeholder="الإيميل" required></div>
                            <div class="col-12">
                                <textarea name="content" class="form-control" rows="4" placeholder="نرحب بمقترحاتكم وتعليقاتكم هنا..." required></textarea>
                            </div>
                            <div class="col-12 text-center mt-3"><button type="submit" class="btn btn-publish">إضافة تعليقك
                                    الآن</button></div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
    </main>
@endsection
