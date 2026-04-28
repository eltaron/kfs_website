@extends('layouts.app')

@section('title', 'كل البرامج التدريبية')
@push('css')
    <link rel="stylesheet" href="{{ asset('css') }}/estidama.css">
@endpush
@section('content')
    <main class="main-content">
        {{-- Page Header --}}
        <header class="page-header" style="background-image: url('{{ asset('images/bg/estidama.jpeg') }}');">
            <div class="container text-center">
                @if (!empty($settings['estidama_logo_white']))
                    <img src="{{ Storage::url($settings['estidama_logo_white']) }}" alt="شعار استدامة" class="hero-logo">
                @endif
                <h1 class="hero-title">البرامج التدريبية</h1>
                <p class="hero-subtitle">استكشف جميع البرامج المتاحة والمستقبلية التي يقدمها مركز استدامة.</p>
            </div>
        </header>
        <div class="container py-5">
            @if ($trainingPrograms->isNotEmpty())
                <div class="row g-4">
                    @foreach ($trainingPrograms as $program)
                        <div class="col-lg-4 col-md-6">
                            <div class="program-card h-100">
                                <div class="card-image">
                                    <img src="{{ Storage::url($program->image) }}" alt="{{ $program->title }}">
                                    {{-- Status Badge --}}
                                    @if ($program->status == 'open')
                                        <span class="program-status-badge bg-success">التسجيل متاح</span>
                                    @elseif($program->status == 'closed')
                                        <span class="program-status-badge bg-danger">التسجيل مغلق</span>
                                    @else
                                        <span class="program-status-badge bg-info text-dark">جاري التنفيذ</span>
                                    @endif
                                </div>
                                <div class="card-content">
                                    <span class="card-center">{{ $program->trainingCenter->name ?? '' }}</span>
                                    <h4 class="card-title">{{ $program->title }}</h4>
                                    <p class="card-excerpt">
                                        {{ \Illuminate\Support\Str::limit(strip_tags($program->description), 80) }}</p>

                                    {{-- Button logic based on status --}}
                                    @if ($program->status == 'open')
                                        <a href="{{ route('estidama.apply', $program) }}"
                                            class="btn btn-primary w-100 mt-auto">التسجيل في البرنامج</a>
                                    @else
                                        <a href="#" class="btn btn-secondary w-100 mt-auto disabled">التسجيل مغلق</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                {{-- Pagination Links --}}
                <div class="pagination-wrapper mt-5">
                    {{ $trainingPrograms->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <p class="h5">عذرًا، لا توجد برامج تدريبية لعرضها حاليًا.</p>
                </div>
            @endif
        </div>
    </main>
@endsection
