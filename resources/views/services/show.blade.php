@extends('layouts.app')

@section('title', $service->title)

@push('css')
    <link rel="stylesheet" href="{{ asset('css/services.css') }}">
    <style>
        .conditional-field { display: none; }
        .service-form-card { border-top: 5px solid #DAA520; transition: 0.3s; }
        .form-label { color: #1e272e; font-weight: 700; margin-bottom: 10px; }
        .pricing-widget { border-radius: 15px; overflow: hidden; background: #fff;padding:0 }
        .price-breakdown li { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px dashed #eee; }
        .total-box { background: #1e272e; color: #fff; border-radius: 12px; }
        .text-gold { color: #DAA520 !important; }
        .btn-gold { background-color: #DAA520; border-color: #DAA520; color: #fff; transition: 0.3s; }
        .btn-gold:hover { background-color: #c59119; color: #fff; transform: translateY(-2px); }
        .input-group>:not(:first-child):not(.dropdown-menu):not(.valid-tooltip):not(.valid-feedback):not(.invalid-tooltip):not(.invalid-feedback) {
            border-top-left-radius: 5px;
            border-bottom-left-radius: 5px;
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        
    </style>
@endpush

@section('content')
    <main class="main-content">
        <header class="page-header" style="background-image: url('{{ asset('images/bg/services.jpg') }}'); background-size: cover; background-position: center;">
            <div class="container text-center py-5">
                <span class="badge bg-warning text-dark mb-2">{{ $service->parent ? $service->parent->title : 'دليل الخدمات' }}</span>
                <h1 class="text-white fw-bold">{!! $service->title !!}</h1>
            </div>
        </header>

        <div class="container py-5">
            <div class="row g-5 text-end" dir="rtl">
                {{-- قسم الفورم --}}
                <div class="col-lg-8">
                    @if ($service->description)
                        <article class="rich-text-content mb-4 bg-white p-4 rounded-3 shadow-sm">
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
                        <div class="service-form-card bg-white shadow-lg p-5 rounded-4">
                            <div class="text-center mb-5 border-bottom pb-4">
                                <h3 class="fw-bold">استمارة تقديم الطلب الرقمية</h3>
                                <p class="text-muted">نظام الربط الإلكتروني المباشر بمركز المتغيرات المكانية</p>
                            </div>

                            @auth
                                <form action="{{ route('services.submit', $service) }}" method="POST" enctype="multipart/form-data" id="dynamicServiceForm">
                                    @csrf

                                    @foreach ($service->form_fields as $field)
                                        <div class="form-group mb-4" id="group_{{ $field['name'] }}">
                                            <label class="form-label">
                                                {{ $field['label'] }}
                                                @if ($field['is_required'] ?? false) <span class="text-danger">*</span> @endif
                                            </label>

                                            @switch($field['type'])
                                                @case('select')
                                                    <select name="{{ $field['name'] }}" class="form-select p-3 px-5 {{ $field['name'] == 'category' ? 'calc-trigger' : '' }}" required>
                                                        <option value="">-- اختر --</option>
                                                        {{-- إذا كان الحقل هو حقل المناطق، نقوم بتوليد الخيارات من جدول الأسعار تلقائياً --}}
                                                        @if($field['name'] == 'category' && !empty($service->category_pricing))
                                                            @foreach ($service->category_pricing as $priceItem)
                                                                <option value="{{ $priceItem['name'] }}">{{ $priceItem['name'] }}</option>
                                                            @endforeach
                                                        @else
                                                            @foreach ($field['options'] ?? [] as $opt)
                                                                <option value="{{ $opt['value'] }}">{{ $opt['label'] }}</option>
                                                            @endforeach
                                                        @endif
                                                    </select>
                                                @break

                                                @case('file')
                                                    <input type="file" name="{{ $field['name'] }}" class="form-control p-2" @if ($field['is_required'] ?? false) required @endif>
                                                @break

                                                @case('text')
                                                    @if($field['name'] == 'map')
                                                        <div class="input-group">
                                                            <input type="text" name="map" id="map_input" class="form-control p-3" readonly placeholder="اضغط لالتقاط العنوان عبر GPS...">
                                                            <button type="button" onclick="getLocation()" class="btn btn-gold text-white">
                                                                <i class="fas fa-map-marker-alt"></i>
                                                            </button>
                                                        </div>
                                                    @else
                                                        <input type="text" name="{{ $field['name'] }}" class="form-control p-3" @if($field['name'] == 'area') placeholder="أدخل المساحة بالمتر المربع" @endif>
                                                    @endif
                                                @break

                                                @case('number')
                                                    <input type="number" name="{{ $field['name'] }}" class="form-control p-3 {{ in_array($field['name'], ['area', 'quantity']) ? 'calc-trigger' : '' }}" placeholder="0.00">
                                                @break
                                                
                                                @default
                                                    <input type="text" name="{{ $field['name'] }}" class="form-control p-3">
                                            @endswitch
                                        </div>
                                    @endforeach

                                    <div class="mt-4 pt-4 border-top">
                                        <div class="alert alert-info border-0 text-center mb-4">
                                            <button type="button" class="btn btn-link text-decoration-none fw-bold" data-bs-toggle="modal" data-bs-target="#termsModal">
                                                <i class="fas fa-file-contract"></i> الإقرار بالصلاحية والشروط والأحكام
                                            </button>
                                        </div>
                                        <button type="submit" class="btn btn-gold w-100 py-3 fw-bold rounded-pill h4">
                                            تقديم الطلب والانتقال للدفع <i class="fas fa-chevron-left ms-2"></i>
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="alert alert-warning text-center p-4 h5 rounded-4">
                                    يرجى <a href="{{ route('login') }}" class="fw-bold">تسجيل الدخول</a> لتتمكن من تقديم الطلب.
                                </div>
                            @endauth
                        </div>
                    @endif
                </div>

                {{-- السايدبار المالي --}}
                <div class="col-lg-4">
                    <aside class="sidebar-sticky">
                        @if (!$service->children->isNotEmpty())
                            <div class="sidebar-widget pricing-widget mb-4 shadow-lg border-0">
                                <div class="p-4 bg-light border-bottom text-center">
                                    <h5 class="m-0 fw-bold"><i class="fas fa-calculator ms-2 text-gold"></i> تقدير المقابل</h5>
                                </div>

                                <div class="p-4 bg-white">
                                    <ul class="price-breakdown list-unstyled p-0 m-0">
                                        <li><span>سعر المتر الأساسي:</span> <strong id="price_per_meter">0.00</strong></li>
                                        <li><span>مقابل استغلال المكان:</span> <strong id="display_subtotal">0.00</strong></li>
                                        <li><span>تأمين ({{ $service->insurance_percentage }}%):</span> <strong id="display_insurance">0.00</strong></li>
                                        @if ($service->has_vat)
                                            <li><span>ضريبة (14%):</span> <strong id="display_vat">0.00</strong></li>
                                        @endif
                                        <li><span>طابع الشهداء + SMS:</span> <strong>{{ number_format($service->martyr_stamp_fee + $service->sms_fee, 2) }}</strong></li>
                                    </ul>

                                    <div class="total-box text-center p-4 mt-4">
                                        <small class="d-block opacity-75">إجمالي المبلغ المطلوب</small>
                                        <div class="d-flex align-items-center justify-content-center gap-2 mt-1">
                                            <span class="h1 fw-bold text-gold mb-0" id="grandTotalDisplay">15.00</span>
                                            <span class="h4 text-gold mb-0">ج.م</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if ($relatedServices->isNotEmpty())
                            <div class="bg-white p-4 rounded-4 shadow-sm">
                                <h5 class="fw-bold border-bottom pb-2 mb-3">خدمات ذات صلة</h5>
                                @foreach ($relatedServices as $related)
                                    <a href="{{ route('services.show', $related) }}" class="d-flex align-items-center text-dark text-decoration-none mb-3 border-bottom pb-2">
                                        <i class="{{ $related->icon ?? 'fas fa-chevron-left' }} text-gold ms-2"></i>
                                        <span class="small">{{ $related->title }}</span>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </aside>
                </div>
            </div>
        </div>
    </main>

    {{-- مودال الشروط --}}
    <div class="modal fade" id="termsModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content rounded-4 shadow-lg border-0 text-end">
                <div class="modal-header border-0 bg-light rounded-top-4">
                    <h5 class="modal-title fw-bold">الضوابط والشروط القانونية</h5>
                </div>
                <div class="modal-body p-4">
                    <p>1. يلتزم مقدم الطلب بصحة كافة البيانات المذكورة.</p>
                    <p>2. يتم حساب المقابل بناءً على الفئات المعتمدة بالمحافظة والمساحة الفعلية.</p>
                    <p>3. المقابل المدفوعة لا تُسترد في حال البدء في المعاينة الميدانية.</p>
                </div>
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-gold rounded-pill px-4" data-bs-dismiss="modal">موافق</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // 1. وظيفة الـ GPS
        async function getLocation() {
            if (navigator.geolocation) {
                const btn = event.currentTarget;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';
                navigator.geolocation.getCurrentPosition(async (pos) => {
                    try {
                        const res = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${pos.coords.latitude}&lon=${pos.coords.longitude}&format=json&accept-language=ar`);
                        const data = await res.json();
                        document.getElementById('map_input').value = data.display_name;
                    } catch (e) { alert("تعذر جلب العنوان."); }
                    btn.innerHTML = '<i class="fas fa-map-marker-alt"></i>';
                });
            }
        }

        // 2. محرك الأسعار الموحد
        const pricingConfig = {
            categoryData: {!! json_encode($service->category_pricing ?? []) !!},
            insuranceRate: {{ $service->insurance_percentage ?? 0 }},
            fixedFees: {{ $service->martyr_stamp_fee + $service->sms_fee }},
            hasVat: {{ $service->has_vat ? 1 : 0 }}
        };

        function runCalculation() {
            // جلب القيم - تأكد من مطابقة أسماء الـ inputs
            const areaInput = document.querySelector('[name="area"]') || document.querySelector('[name="number"]');
            const area = parseFloat(areaInput?.value) || 0;
            const categoryName = document.querySelector('[name="category"]')?.value;

            let pricePerMeter = 0;
            const match = pricingConfig.categoryData.find(c => c.name === categoryName);
            if (match) {
                pricePerMeter = parseFloat(match.price_multiplier);
            }

            // الحسابات
            const subtotal = area * pricePerMeter;
            const insurance = subtotal * (pricingConfig.insuranceRate / 100);
            const vat = pricingConfig.hasVat ? (subtotal * 0.14) : 0;
            const total = subtotal + insurance + vat + pricingConfig.fixedFees;

            // تحديث الواجهة
            document.getElementById('price_per_meter').innerText = pricePerMeter.toLocaleString();
            document.getElementById('display_subtotal').innerText = subtotal.toLocaleString();
            document.getElementById('display_insurance').innerText = insurance.toLocaleString();
            if (pricingConfig.hasVat) {
                document.getElementById('display_vat').innerText = vat.toLocaleString();
            }
            document.getElementById('grandTotalDisplay').innerText = total.toLocaleString('en-US', {
                minimumFractionDigits: 2
            });
        }

        // الاستماع للتغيرات في كل حقول الفورم التي تؤثر على السعر
        const form = document.getElementById('dynamicServiceForm');
        if (form) {
            form.addEventListener('input', runCalculation);
            form.addEventListener('change', runCalculation);
        }
    </script>
@endpush