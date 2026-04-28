@extends('layouts.app')
@section('title', 'غير مسموح بالدخول')
@section('content')
    <div class="container text-center py-5"
        style="min-height: 70vh; display: flex; flex-direction: column; justify-content: center; align-items: center;">
        <div class="mb-4">
            <i class="fas fa-user-shield fa-8x" style="color: #ff4757;"></i>
        </div>
        <h2 class="fw-bold mb-3" style="color: #1e272e;">منطقة محظورة!</h2>
        <h4 class="text-danger mb-3">خطأ 403 - صلاحيات غير كافية</h4>
        <p class="text-muted mb-5" style="max-width: 600px; font-size: 1.1rem;">نعتذر منك، ليس لديك الصلاحية للدخول إلى هذا
            القسم. هذا الجزء مخصص لموظفي ديوان عام المحافظة فقط بناءً على هويتك الرقمية.</p>

        <div class="d-flex gap-3">
            <a href="{{ url()->previous() }}" class="btn btn-outline-dark px-4 py-2 rounded-pill">تراجع للخلف</a>
            <a href="{{ route('citizen.dashboard') }}" class="btn btn-gold px-4 py-2 rounded-pill fw-bold"
                style="background: #e1b12c; color: #1e272e; border:none;">لوحة تحكم المواطن</a>
        </div>
    </div>
@endsection
