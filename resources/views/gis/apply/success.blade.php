@extends('layouts.app')
@section('title', 'تم إرسال الطلب بنجاح')

@section('content')
    <div class="container py-5 text-center" dir="rtl" style="min-height: 70vh;">
        <div class="success-card bg-white shadow-lg p-5 rounded-4 border-top border-5 border-success">
            <div class="mb-4">
                <i class="fas fa-check-circle fa-7x text-success animate__animated animate__bounceIn"></i>
            </div>

            <h2 class="fw-black mb-3" style="color: #1e272e;"> تم إرسال طلبكم بنجاح</h2>
            <p class="lead text-muted mb-4">لقد تم تسجيل المعاملة بنجاح. يرجى الاحتفاظ برقم
                المعاملة التالي للمتابعة.</p>

            <div class="ticket-box p-4 bg-light rounded-4 mb-5 border-dashed border-primary"
                style="display:inline-block; min-width: 300px;">
                <p class="small mb-2 text-uppercase fw-bold text-muted">رقم المعاملة (Ticket ID)</p>
                <h3 class="fw-black text-navy m-0">{{ $submission->id }}</h3>
                <button class="btn btn-sm btn-link text-primary mt-2" onclick="copyToClipboard('{{ $submission->id }}')"><i
                        class="fas fa-copy"></i> نسخ الرقم</button>
            </div>

            <div class="next-steps-info text-end mx-auto" style="max-width: 600px;">
                <h6 class="fw-bold mb-3 text-right"><i class="fas fa-info-circle ms-2"></i> ماذا سيحدث الآن؟</h6>
                <ul class="list-unstyled">
                    <li class="mb-2">✅ ستصلك رسالة SMS تأكيدية خلال لحظات.</li>
                    <li class="mb-2">🕵️‍♂️ سيقوم الفريق الفني بمراجعة البيانات والمستندات.</li>
                    <li>📍 سيتم تحديد موعد للمعاينة الميدانية .</li>
                </ul>
            </div>

            <div class="mt-5 d-flex gap-3 justify-content-center">
                <button onclick="window.print()" class="btn btn-outline-dark px-4 py-2 rounded-pill"><i
                        class="fas fa-print me-2"></i> طباعة الإفادة</button>
            </div>
        </div>
    </div>

    <script>
        function copyToClipboard(text) {
            navigator.clipboard.writeText(text);
            alert('تم نسخ رقم المعاملة');
        }
    </script>
@endsection
