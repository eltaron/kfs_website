@extends('layouts.app')

@section('title', 'تم إرسال الطلب بنجاح')

@section('content')
    <style>
        .success-card {
            background: #fff;
            border-radius: 24px;
            padding: 50px 30px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.08);
        }

        .success-icon {
            font-size: 90px;
            color: #22c55e;
            margin-bottom: 20px;
        }

        .success-card h2 {
            font-weight: 700;
            color: #1f2937;
        }

        .success-card p {
            font-size: 1.05rem;
        }
    </style>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 text-center">
                <div class="success-card">
                    <div class="success-icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <h2>تم إرسال طلبك بنجاح 🎉</h2>
                    <p class="text-muted mt-2">
                        تم استلام طلبك وسيتم مراجعته من قبل المختصين في أقرب وقت.
                    </p>

                    <a href="{{ route('services.index') }}" class="btn btn-primary mt-4 px-5">
                        العودة إلى الخدمات
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
