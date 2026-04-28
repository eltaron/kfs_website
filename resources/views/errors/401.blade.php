@extends('layouts.app')
@section('title', 'الوصول مقيد')
@section('content')
    <div class="container text-center py-5"
        style="min-height: 70vh; display: flex; flex-direction: column; justify-content: center; align-items: center;">
        <div class="mb-4">
            <div
                style="width: 150px; height: 150px; background: rgba(30, 39, 46, 0.05); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                <i class="fas fa-lock fa-4x" style="color: #1e272e;"></i>
            </div>
        </div>
        <h2 class="fw-bold mb-3" style="color: #1e272e;">يرجى تأكيد هويتك أولاً</h2>
        <p class="text-muted mb-5" style="max-width: 500px;">هذه الصفحة محمية بنظام الهوية الرقمية. يرجى تسجيل الدخول للوصول
            إلى كافة خدمات البوابة الإلكترونية لمحافظة كفر الشيخ.</p>

        <a href="{{ route('login') }}" class="btn btn-primary px-5 py-3 rounded-pill fw-bold"
            style="background: #1e272e; border:none; box-shadow: 0 10px 20px rgba(0,0,0,0.1);">
            تسجيل الدخول الآن <i class="fas fa-sign-in-alt ms-2"></i>
        </a>
    </div>
@endsection
