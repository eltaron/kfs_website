@extends('layouts.app')

@section('title', $service->title)
@push('css')
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
    <style>
        /* تأكد من إخفاء الحقول المشروطة فقط، مع ضمان ظهور الحقول الأساسية */
        .conditional-field {
            display: none;
        }

        /* تعديل المسافة بسبب الهيدر الثابت */
        .main-content {
            padding-top: 50px;
        }

        .service-form-card {
            border-top: 5px solid var(--tm-gold);
            transition: 0.3s;
        }

        .form-label {
            color: #1e272e;
            font-weight: 700;
            margin-bottom: 10px;
        }
    </style>
@endpush

@section('content')
    <main class="main-content">
        <header class="page-header" style="background-image: url('{{ asset('images/bg/services.jpg') }}');">
            <div class="container text-center py-5">
                <span class="badge-gold mb-2">{{ $service->parent ? $service->parent->title : 'دليل الخدمات' }}</span>
                <h1 class="text-white fw-900">{{ $service->title }}</h1>
            </div>
        </header>

        <div class="container py-5">
            <div class="row g-5">
                {{-- قسم الفورم --}}
                <div class="col-lg-8">
                    @if ($service->description)
                        <article class="rich-text-content mb-5 bg-white p-4 rounded-3 shadow-sm">
                            {!! $service->description !!}
                        </article>
                    @endif
                    @if ($service->children->isNotEmpty())
                        <div class="sub-services-section mt-5">
                            <h3 class="section-title">الخدمات الفرعية المتاحة</h3>
                            <div class="row g-3">
                                @foreach ($service->children as $subService)
                                    <div class="col-md-6 col-lg-4">
                                        <a href="{{ $subService->link ? $subService->link : route('services.show', $subService) }}"
                                            class="sub-service-card" {{ $subService->link ? 'target="_blank"' : '' }}>
                                            <div class="card-icon">
                                                <i class="{{ $subService->icon ?? 'fas fa-file-alt' }}"></i>
                                                {{-- Uses sub-service icon or a default one --}}
                                            </div>
                                            <div class="card-title">
                                                {{ $subService->title }}
                                            </div>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="service-form-card bg-white shadow p-5 rounded-4">
                            <div class="text-center mb-5 border-bottom pb-4">
                                <h3 class="fw-bold">استمارة تقديم الطلب الرقمية</h3>
                                <p class="text-muted">نظام الربط الإلكتروني المباشر بمركز المتغيرات المكانية</p>
                            </div>

                            @auth
                                @if (is_array($service->form_fields) && count($service->form_fields) > 0)
                                    <form action="{{ route('services.submit', $service) }}" method="POST"
                                        enctype="multipart/form-data" id="dynamicServiceForm">
                                        @csrf

                                        @foreach ($service->form_fields as $field)
                                            <div class="form-group mb-4 @if (isset($field['is_conditional']) && $field['is_conditional']) conditional-field @endif"
                                                id="group_{{ $field['name'] }}"
                                                data-depends-on="{{ $field['depends_on'] ?? '' }}"
                                                data-depends-val="{{ is_array($field['depends_on_value'] ?? '') ? json_encode($field['depends_on_value']) : $field['depends_on_value'] ?? '' }}">

                                                <label class="form-label d-block text-end">
                                                    {{ $field['label'] }}
                                                    @if ($field['is_required'] ?? false)
                                                        <span class="text-danger">*</span>
                                                    @endif
                                                </label>

                                                @switch($field['type'])
                                                    @case('select')
                                                        <select name="{{ $field['name'] }}" class="form-select select-trigger p-3 px-5"
                                                            @if (!isset($field['is_conditional'])) required @endif>
                                                            <option value="">-- اختر --</option>
                                                            @foreach ($field['options'] ?? [] as $opt)
                                                                <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                                                            @endforeach
                                                        </select>
                                                    @break

                                                    @case('db_select')
                                                        <select name="{{ $field['name'] }}" class="form-select p-3">
                                                            <option value="">-- اختر من السجلات المتاحة --</option>
                                                            @php
                                                                $options = [];
                                                                if ($field['table'] == 'markazs') {
                                                                    $options = \App\Models\Markaz::all();
                                                                } elseif ($field['table'] == 'shiakhas') {
                                                                    $options = \App\Models\Shiakha::all();
                                                                } elseif ($field['table'] == 'villages') {
                                                                    $options = \App\Models\Village::all();
                                                                }
                                                            @endphp
                                                            @foreach ($options as $item)
                                                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    @break

                                                    @case('number')
                                                        <input type="number" name="{{ $field['name'] }}"
                                                            class="form-control p-3 @if (isset($field['formula_field']) || isset($field['formula_price_per_unit'])) calc-trigger @endif"
                                                            data-mult="{{ $field['calculation_multiplier'] ?? ($field['formula_price_per_unit'] ?? 0) }}">
                                                    @break

                                                    @case('file')
                                                        <input type="file" name="{{ $field['name'] }}" class="form-control p-2">
                                                    @break

                                                    @default
                                                        <input type="text" name="{{ $field['name'] }}" class="form-control p-3">
                                                @endswitch
                                            </div>
                                        @endforeach

                                        <div class="cta-submit-area mt-5 pt-4 border-top">
                                            <div class="alert alert-light text-center border py-3 mb-4 rounded-3">
                                                <i class="fas fa-lock text-success me-2"></i> يتم تأمين اتصالك وتشفير بياناتك
                                                قبل
                                                التحويل للدفع.
                                            </div>
                                            <button type="submit"
                                                class="btn btn-gold w-100 py-3 fw-900 rounded-pill shadow-lg h4">
                                                تقديم الطلب والانتقال للدفع <i class="fas fa-chevron-left ms-2"></i>
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    <div class="alert alert-info">لا يوجد حقول إضافية مطلوبة لهذه الخدمة، يمكنك الضغط على
                                        "تقديم"
                                        مباشرة.</div>
                                @endif
                            @else
                                <div class="alert alert-warning text-center p-4">يرجى تسجيل الدخول أولاً للتمكن من ملء
                                    الاستمارة.
                                </div>
                            @endauth
                        </div>
                    @endif
                </div>

                {{-- السايدبار المالي --}}
                <div class="col-lg-4">
                    <aside class="sidebar-sticky">
                        @if (!$service->children->isNotEmpty())
                            <div class="sidebar-widget pricing-widget mb-4 shadow-sm border-0">
                                <h5 class="widget-title"><i class="fas fa-receipt me-2 text-gold"></i> ملخص الرسوم</h5>

                                @php
                                    $vatAmount = $service->has_vat ? $service->base_price * 0.14 : 0;
                                    $initialTotal =
                                        $service->base_price +
                                        $vatAmount +
                                        $service->martyr_stamp_fee +
                                        $service->sms_fee;
                                @endphp

                                <ul class="price-breakdown list-unstyled">
                                    <li><span>رسم الخدمة</span> <strong>{{ number_format($service->base_price, 2) }}
                                            ج.م</strong></li>

                                    @if ($service->has_vat)
                                        <li class="vat-line"><span>ضريبة (14%)</span> <span>+
                                                {{ number_format($vatAmount, 2) }} ج.م</span></li>
                                    @endif

                                    <li><span>دمغة شهداء</span> <span>{{ number_format($service->martyr_stamp_fee, 2) }}
                                            ج.م</span></li>
                                    <li><span>خدمة SMS</span> <span>{{ number_format($service->sms_fee, 2) }} ج.م</span>
                                    </li>
                                </ul>

                                <div class="total-box text-center p-3 rounded bg-light mt-3 border border-gold">
                                    <span class="small d-block text-muted">إجمالي تكاليف الطلب (تقديرياً)</span>
                                    <span class="h2 fw-900 text-dark m-0"
                                        id="grandTotalDisplay">{{ number_format($initialTotal, 2) }}</span>
                                    <span class="fw-bold">جنيه</span>
                                    <div id="priceCalculationHint" class="price-updated-notif"></div>
                                </div>
                            </div>
                        @endif

                        {{-- خدمات ذات صلة --}}
                        @if ($relatedServices->isNotEmpty())
                            <div class="sidebar-widget bg-white p-4 rounded-3 shadow-sm">
                                <h5 class="widget-title mb-3 border-bottom pb-2">خدمات ذات صلة</h5>
                                @foreach ($relatedServices as $related)
                                    <a href="{{ route('services.show', $related) }}" class="related-service-item">
                                        <div class="icon">
                                            {{-- Display parent icon if available, otherwise a default --}}
                                            <i class="{{ $related->icon ?? 'fas fa-arrow-left' }}"></i>
                                        </div>
                                        <div class="title">
                                            {{ $related->title }}
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </aside>
                </div>
            </div>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('dynamicServiceForm');
            if (!form) return;

            function handleConditions() {
                document.querySelectorAll('.form-group').forEach(group => {
                    const dependency = group.dataset.dependsOn;
                    if (dependency) {
                        const triggerInput = form.querySelector(`[name="${dependency}"]`);
                        if (triggerInput) {
                            const dependsValRaw = group.dataset.dependsVal;
                            // تحويل القيم لمصفوفة لتدعم تعدد الخيارات (مثل ردي وفقد في آن واحد)
                            let allowedValues = dependsValRaw.startsWith('[') ? JSON.parse(dependsValRaw) :
                                [dependsValRaw];

                            if (allowedValues.includes(triggerInput.value)) {
                                group.style.display = 'block';
                                group.querySelectorAll('input, select, textarea').forEach(el => el
                                    .disabled = false);
                            } else {
                                group.style.display = 'none';
                                group.querySelectorAll('input, select, textarea').forEach(el => el
                                    .disabled = true);
                            }
                        }
                    }
                });
            }

            form.addEventListener('change', handleConditions);
            handleConditions(); // التشغيل الأول فور فتح الصفحة

            // --- منطق الحساب المباشر للرفع المساحي ---
            const serviceBase = {{ $service->base_price }};
            const staticFees = {{ $service->martyr_stamp_fee + $service->sms_fee }};
            const hasVat = {{ $service->has_vat ? 1 : 0 }};

            function updatePrice() {
                let addedCost = 0;
                form.querySelectorAll('.calc-trigger').forEach(input => {
                    addedCost += (parseFloat(input.value) || 0) * (parseFloat(input.dataset.mult) || 0);
                });

                const newBase = serviceBase + addedCost;
                const newVat = hasVat ? (newBase * 0.14) : 0;
                const newTotal = newBase + newVat + staticFees;

                document.getElementById('totalDisplay').innerText = newTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2
                });
            }

            form.addEventListener('input', (e) => {
                if (e.target.classList.contains('calc-trigger')) updatePrice();
            });
        });
    </script>
@endpush
