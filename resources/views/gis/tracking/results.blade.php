@extends('layouts.app')
@section('title', 'تتبع المعاملة - ' . $submission->id)

@push('css')
    <style>
        .timeline-tracker {
            list-style: none;
            padding: 0;
            position: relative;
        }

        .timeline-tracker::after {
            content: '';
            position: absolute;
            right: 20px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: #f1f2f6;
            z-index: 1;
        }

        .track-item {
            position: relative;
            padding: 30px 60px 30px 0;
            z-index: 2;
        }

        .track-dot {
            position: absolute;
            right: 10px;
            top: 35px;
            width: 25px;
            height: 25px;
            border-radius: 50%;
            background: #ddd;
            border: 4px solid #fff;
        }

        .item-active .track-dot {
            background: #e1b12c;
            box-shadow: 0 0 0 5px rgba(225, 177, 44, 0.2);
        }

        .item-completed .track-dot {
            background: #27ae60;
        }

        .item-completed .track-dot::after {
            content: '\f00c';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            color: #fff;
            font-size: 10px;
            display: block;
            text-align: center;
        }
    </style>
@endpush

@section('content')
    <div class="container py-5">
        <div class="row g-4 justify-content-center" dir="rtl">
            <div class="col-lg-10">

                <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
                    <div class="card-header bg-navy text-white p-4">
                        <h5 class="m-0 fw-bold"><i class="fas fa-id-card-alt ms-2"></i> حالة طلب خدمة:
                            {{ $submission->subService->name }}</h5>
                    </div>

                    <div class="card-body p-5">
                        <div class="row mb-5">
                            <div class="col-md-4"><label class="small text-muted d-block">كود الطلب:</label><span
                                    class="fw-bold">{{ $submission->id }}</span></div>
                            <div class="col-md-4"><label class="small text-muted d-block">تاريخ التقديم:</label><span
                                    class="fw-bold">{{ $submission->created_at->format('Y/m/d') }}</span></div>
                            <div class="col-md-4">
                                <label class="small text-muted d-block">المركز / القرية:</label>
                                <span
                                    class="fw-bold text-success">{{ $submission->address_info['details'] ?? 'مركز كفر الشيخ' }}</span>
                            </div>
                        </div>

                        <h5 class="fw-bold border-bottom pb-3 mb-4">المسار الحالي للمعاملة</h5>
                        <div class="timeline-tracker">

                            {{-- 1. الاستلام --}}
                            <div class="track-item item-completed">
                                <div class="track-dot"></div>
                                <h6 class="fw-bold">تم استلام الطلب والملحقات</h6>
                                <p class="small text-muted">اكتمل تسجيل بياناتك وإرسالها لموظف المتغيرات المكانية.</p>
                            </div>

                            {{-- 2. الفحص والمراجعة --}}
                            <div
                                class="track-item {{ in_array($submission->status, ['processing', 'completed']) ? 'item-completed' : 'item-active' }}">
                                <div class="track-dot"></div>
                                <h6 class="fw-bold">المراجعة والتدقيق التخطيطي</h6>
                                @if ($submission->status == 'received')
                                    <p class="small text-muted italic">هذا الطلب بانتظار بدء الفحص الهندسي والتقني من قِبل
                                        الفريق المختص.</p>
                                @else
                                    <p class="small text-muted text-success fw-bold">الطلب قيد المراجعة الفنية الآن.</p>
                                @endif
                            </div>

                            {{-- 3. المخرجات --}}
                            <div class="track-item {{ $submission->status == 'completed' ? 'item-completed' : '' }}">
                                <div class="track-dot"></div>
                                <h6 class="fw-bold">إصدار المحررات المؤمنة والشهادات</h6>
                                <p class="small text-muted">في حالة اكتمال المراجعة، ستظهر هنا روابط الشهادة والمخرجات بصورة
                                    فضائية.</p>
                            </div>

                        </div>

                        @if ($submission->admin_notes)
                            <div class="alert alert-light border p-4 rounded-3 mt-4">
                                <h6 class="fw-bold text-danger">⚠️ ملاحظات هامة:</h6>
                                <div class="small">{!! $submission->admin_notes !!}</div>
                            </div>
                        @endif

                    </div>
                </div>

                <div class="text-center mt-5">
                    <a href="{{ route('home') }}" class="btn btn-navy-outline rounded-pill px-5 py-3">العودة للرئيسية <i
                            class="fas fa-home ms-2"></i></a>
                </div>

            </div>
        </div>
    </div>
@endsection
