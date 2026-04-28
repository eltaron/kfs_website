@extends('layouts.app')
@section('title', 'تفاصيل الطلب #' . $submission->id)

@push('css')
    <style>
        .detail-card {
            background: #fff;
            border-radius: 20px;
            border: none;
        }

        .status-header {
            padding: 40px;
            border-radius: 20px 20px 0 0;
            color: #fff;
            text-align: center;
        }

        /* ألوان هيدر الحالة */
        .bg-pending {
            background: linear-gradient(135deg, #f39c12, #e67e22);
        }

        .bg-awaiting_payment {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
        }

        .bg-paid {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
        }

        .bg-completed {
            background: linear-gradient(135deg, #2c3e50, #34495e);
        }

        .data-table-custom tr td {
            padding: 15px;
            border-bottom: 1px solid #f1f5f9;
        }

        .data-label {
            font-weight: 800;
            color: #4b6584;
            width: 30%;
        }

        .data-value {
            color: #1e272e;
        }

        /* Timeline Tracker */
        .track-step {
            display: flex;
            align-items: center;
            margin-bottom: 20px;
        }

        .step-circle {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: #ddd;
            margin-left: 15px;
        }

        .step-active .step-circle {
            background: #e1b12c;
            box-shadow: 0 0 0 5px rgba(225, 177, 44, 0.2);
        }

        .step-text h6 {
            margin: 0;
            font-weight: bold;
        }

        .print-btn {
            background: #1e272e;
            color: #fff;
            border-radius: 50px;
            padding: 8px 25px;
            transition: 0.3s;
            border: none;
        }

        .print-btn:hover {
            background: #e1b12c;
            color: #1e272e;
        }
    </style>
@endpush

@section('content')
    <div class="container py-5">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-10">
                {{-- زر العودة --}}
                <div class="mb-4 text-end">
                    <a href="{{ route('citizen.dashboard') }}" class="text-decoration-none text-muted">
                        <i class="fas fa-arrow-right ms-1"></i> العودة للوحة التحكم
                    </a>
                </div>

                <div class="detail-card shadow-lg mb-5">
                    {{-- هيدر الحالة --}}
                    <div class="status-header bg-{{ $submission->status }}">
                        <i class="fas fa-file-invoice-dollar fa-3x mb-3"></i>
                        <h2 class="fw-bold">رقم الطلب: #{{ $submission->id }}</h2>
                        <p class="mb-0">نوع الخدمة: {{ $submission->service->title }}</p>
                    </div>

                    <div class="p-5">
                        <div class="row g-5">
                            {{-- معلومات مقدم الطلب --}}
                            <div class="col-md-12">
                                <h4 class="fw-bold mb-4 text-navy">بيانات النموذج المقدم</h4>
                                <table class="table data-table-custom">
                                    @foreach ($submission->submitted_data as $key => $value)
                                        @php
                                            // محاولة إيجاد الاسم العربي من فورم فيلدز لو كان موجوداً
                                            $fieldInfo = collect($submission->service->form_fields)->firstWhere(
                                                'name',
                                                $key,
                                            );
                                            $label = $fieldInfo['label'] ?? $key;
                                        @endphp
                                        <tr>
                                            <td class="data-label">{{ $label }}:</td>
                                            <td class="data-value">
                                                {{-- التحقق لو كانت البيانات ملف يتم عرض لينك --}}
                                                @if (is_string($value) && (strpos($value, 'http') === 0 || strpos($value, 'files/') === 0))
                                                    <a href="{{ Storage::url($value) }}" target="_blank"
                                                        class="btn btn-sm btn-outline-primary py-0">مشاهدة المرفق</a>
                                                @else
                                                    {{ is_array($value) ? implode(', ', $value) : $value }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </table>
                            </div>

                            {{-- مسار المراجعة (Timeline) --}}
                            <div class="col-md-12 border-start">
                                <h4 class="fw-bold mb-4 text-navy">تتبع الحالة</h4>
                                <div class="tracking-list p-3 bg-light rounded-4">
                                    <div class="track-step step-active">
                                        <div class="step-circle"></div>
                                        <div class="step-text">
                                            <h6>تم إرسال الطلب إلكترونياً</h6><small
                                                class="text-muted">{{ $submission->created_at->format('Y/m/d') }}</small>
                                        </div>
                                    </div>

                                    <div
                                        class="track-step {{ in_array($submission->status, ['awaiting_payment', 'paid', 'completed']) ? 'step-active' : '' }}">
                                        <div class="step-circle"></div>
                                        <div class="step-text">
                                            <h6>جاري فحص المستندات مالياً وفنياً</h6>
                                        </div>
                                    </div>

                                    <div
                                        class="track-step {{ in_array($submission->status, ['paid', 'completed']) ? 'step-active' : '' }}">
                                        <div class="step-circle"></div>
                                        <div class="step-text">
                                            <h6>تأكيد عملية الدفع الرقمي</h6>
                                        </div>
                                    </div>

                                    <div class="track-step {{ $submission->status == 'completed' ? 'step-active' : '' }}">
                                        <div class="step-circle"></div>
                                        <div class="step-text">
                                            <h6>تم تنفيذ الخدمة وإصدار المخرجات</h6>
                                        </div>
                                    </div>
                                </div>

                                @if ($submission->admin_notes)
                                    <div class="alert alert-info mt-4">
                                        <h6 class="fw-bold"><i class="fas fa-bullhorn me-2"></i> ملاحظات الإدارة:</h6>
                                        <p class="mb-0 small">{!! $submission->admin_notes !!}</p>
                                    </div>
                                @endif

                                {{-- خيارات الطباعة والدفع --}}
                                <div class="mt-5 pt-4 d-grid gap-2">
                                    @if ($submission->status == 'awaiting_payment')
                                        <button class="btn btn-warning py-3 fw-bold shadow-sm rounded-pill">سدد الآن
                                            <i class="fas fa-credit-card me-2"></i></button>
                                    @endif
                                    <button onclick="window.print()" class="print-btn py-2">
                                        <i class="fas fa-print me-2"></i> طباعة ملخص الطلب
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
