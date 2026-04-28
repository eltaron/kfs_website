@extends('layouts.app')

@section('title', 'الفرص الاستثمارية')
@push('css')
    <link rel="stylesheet" href="{{ asset('css') }}/investments.css">
@endpush
@section('content')
    <main class="main-content">
        {{-- Page Header --}}
        <header class="page-header" style="background-image: url('{{ asset('images/bg/investment.jpg') }}');">
            <div class="container text-center">
                <h1>الفرص الاستثمارية في المحافظة</h1>
                <p>اكتشف آفاقًا جديدة للنمو والتطور في مختلف قطاعات المحافظة الواعدة.</p>
            </div>
        </header>

        <div class="container py-5">
            @if ($investments->isNotEmpty())
                <div class="row g-4">
                    @foreach ($investments as $investment)
                        <div class="col-lg-6 col-md-6">
                            <div class="investment-archive-card">
                                <div class="card-image">
                                    <a href="{{ route('investments.show', $investment) }}">
                                        <img src="{{ Storage::url($investment->thumbnail) }}"
                                            alt="{{ $investment->title }}">
                                    </a>
                                </div>
                                <div class="card-content">
                                    <h3 class="card-title">
                                        <a href="{{ route('investments.show', $investment) }}">
                                            {{ $investment->title }}
                                        </a>
                                    </h3>
                                    @if ($investment->description)
                                        <p class="card-excerpt">
                                            {{ \Illuminate\Support\Str::limit(strip_tags($investment->description), 100) }}
                                        </p>
                                    @endif
                                    <div class="card-footer">
                                        <a href="{{ route('investments.show', $investment) }}" class="details-link">
                                            عرض التفاصيل <i class="fas fa-arrow-left"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination Links --}}
                <div class="pagination-wrapper mt-5">
                    {{ $investments->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <p class="h5">عذرًا، لا توجد فرص استثمارية لعرضها حاليًا.</p>
                </div>
            @endif
        </div>
        {{-- Investment Map CTA Section --}}
        <section class="investment-cta-wrapper py-5">
            <div class="container">
                <div class="cta-card">
                    <div class="row align-items-center">
                        <div class="col-lg-8 text-center text-lg-end">
                            <div class="cta-content">
                                <div class="cta-badge">فرص استثمارية إضافية</div>
                                <h2 class="cta-title">هل تبحث عن خريطة أشمل للاستثمار في مصر؟</h2>
                                <p class="cta-text">
                                    يمكنك الآن تصفح المزيد من الفرص التفاعلية والمشروعات القومية عبر بوابة
                                    <strong>خريطة مصر الاستثمارية</strong> الرسمية التابعة لوزارة الاستثمار.
                                </p>
                            </div>
                        </div>
                        <div class="col-lg-4 text-center mt-4 mt-lg-0">
                            <a href="https://www.investinegypt.gov.eg/arabic/pages/ExploreMap.aspx?cat=2&subcat=15"
                                target="_blank" class="btn btn-cta-map">
                                <span>استكشف الخريطة التفاعلية</span>
                                <i class="fas fa-map-marked-alt"></i>
                            </a>
                        </div>
                    </div>
                    {{-- زخرفة خلفية بسيطة --}}
                    <div class="cta-decoration"></div>
                </div>
            </div>
        </section>
    </main>
@endsection
