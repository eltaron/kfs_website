@extends('layouts.app')
@section('title', 'الصفحة غير موجودة')
@section('content')
    <div class="container text-center py-5"
        style="min-height: 70vh; display: flex; flex-direction: column; justify-content: center; align-items: center;">
        <div class="error-visual mb-4" style="position: relative;">
            <i class="fas fa-map-marked-alt fa-7x" style="color: #e1b12c; opacity: 0.2;"></i>
            <h1
                style="font-size: 8rem; font-weight: 900; color: #1e272e; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); margin: 0;">
                404</h1>
        </div>
        <h2 class="fw-bold mb-3" style="color: #1e272e;">عفواً.. تهت في الطريق</h2>
        <p class="text-muted mb-5" style="max-width: 500px; font-size: 1.1rem;">الصفحة التي تبحث عنها قد تكون حُذفت أو انتقلت
            لرابط آخر بداخل بوابة المحافظة الرقمية.</p>
        <a href="{{ route('home') }}" class="btn btn-gold px-5 py-3 rounded-pill fw-bold"
            style="background: #e1b12c; color: #1e272e; border:none; box-shadow: 0 10px 20px rgba(225, 177, 44, 0.3);">
            العودة للرئيسية <i class="fas fa-home ms-2"></i>
        </a>
    </div>
@endsection
