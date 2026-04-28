@extends('layouts.app')
@section('title', 'تقديم طلب - ' . $service->name)

@push('css')
    <link rel="stylesheet" href="{{ asset('css/gis-services.css') }}">
    <style>
        .step-header {
            display: flex;
            justify-content: center;
            gap: 40px;
            margin-bottom: 40px;
            position: relative;
        }

        .step-dot {
            text-align: center;
            z-index: 2;
            position: relative;
            cursor: not-allowed;
        }

        .step-dot .circle {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-weight: 900;
            font-size: 1.2rem;
            margin-bottom: 8px;
            transition: 0.3s;
        }

        .step-active .circle {
            background: #16a085;
            box-shadow: 0 0 15px rgba(22, 160, 133, 0.4);
            transform: scale(1.1);
        }

        .step-header::after {
            content: '';
            height: 2px;
            background: #ddd;
            position: absolute;
            width: 60%;
            top: 25px;
            z-index: 1;
        }

        .wizard-card {
            background: #fff;
            border-radius: 25px;
            border-right: 8px solid #e1b12c;
        }

        .conditional-section {
            display: none;
        }

        .fw-950 {
            font-weight: 950 !important;
        }

        .text-navy {
            color: #1e272e !important;
        }
    </style>
@endpush

@section('content')
    <div class="container py-5" dir="rtl">
        {{-- الخطوات --}}
        <div class="step-header text-center">
            <div class="step-dot step-active" id="s1-head">
                <div class="circle">1</div>
                <p class="small">البيانات الشخصية</p>
            </div>
            <div class="step-dot" id="s2-head">
                <div class="circle">2</div>
                <p class="small">بيانات الموقع</p>
            </div>
            <div class="step-dot" id="s3-head">
                <div class="circle">3</div>
                <p class="small">تفاصيل الطلب</p>
            </div>
            <div class="step-dot" id="s4-head">
                <div class="circle">4</div>
                <p class="small">المرفقات والسداد</p>
            </div>
        </div>

        <form action="{{ route('gis.apply.submit', $service->slug) }}" method="POST" enctype="multipart/form-data"
            id="gisMainForm">
            @csrf

            {{-- المرحلة الأولى: بيانات مقدم الطلب --}}
            <section class="wizard-card p-5 shadow-lg animate__animated animate__fadeIn" id="step1">
                <h4 class="fw-bold border-bottom pb-3 mb-4 text-navy">الخطوة الأولى: بيانات مقدم الطلب</h4>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">اسم المالك (تلقائي)</label>
                        <input type="text" class="form-control bg-light" value="{{ auth()->user()->name }}" readonly>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-navy">صفة مقدم الطلب</label>
                        <select name="applicant_type" id="applicant_type" class="form-select" required
                            onchange="toggleAgentInfo(this.value)">
                            <option value="owner">المالك الأصيل</option>
                            <option value="agent">وكيل بموجب توكيل</option>
                        </select>
                    </div>

                    <div class="col-md-12">
                        <div class="p-4 rounded-3 bg-light border">
                            <label class="form-label fw-bold">إرفاق صورة بطاقة الرقم القومي للمالك</label>
                            <input type="file" name="owner_id_card" class="form-control">
                        </div>
                    </div>

                    <div class="col-md-12 conditional-section" id="agent-side">
                        <div class="p-4 rounded-3 bg-white border-start border-4 border-warning shadow-sm">
                            <h6 class="fw-bold text-warning mb-3">بيانات الوكالة</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">اسم الوكيل رباعي</label>
                                    <input type="text" name="agent_name" class="form-control">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">صورة التوكيل الرسمي</label>
                                    <input type="file" name="proxy_doc" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" onclick="nextStep(2)"
                    class="btn btn-primary-gis px-5 py-3 rounded-pill fw-bold mt-4">المرحلة التالية <i
                        class="fas fa-chevron-left ms-2"></i></button>
            </section>

            {{-- المرحلة الثانية: الموقع الجغرافي --}}
            <section class="wizard-card p-5 shadow-lg d-none" id="step2">
                <h4 class="fw-bold border-bottom pb-3 mb-4 text-navy">الخطوة الثانية: الموقع الجغرافي للعقار</h4>
                <div class="row g-4">
                    <div class="col-md-4">
                        <label class="form-label fw-bold">المركز</label>
                        <select name="markaz_id" id="markaz_select" class="form-select" onchange="loadUnits(this.value)"
                            required>
                            <option value="">-- اختر المركز --</option>
                            @foreach ($markazs as $m)
                                <option value="{{ $m->id }}">{{ $m->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">الوحدة المحلية</label>
                        <select name="shiakha_id" id="unit_select" class="form-select" onchange="loadVillages(this.value)"
                            required>
                            <option value="">بانتظار المركز...</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold">القرية / العزبة</label>
                        <select name="village_id" id="village_select" class="form-select" required>
                            <option value="">بانتظار الوحدة...</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-bold">تفاصيل العنوان بدقة</label>
                        <textarea name="address" class="form-control" rows="2" placeholder="الشارع - المعالم المميزة"></textarea>
                    </div>
                </div>
                <div class="mt-4">
                    <button type="button" onclick="prevStep(1)"
                        class="btn btn-secondary px-5 py-3 rounded-pill fw-bold h4">السابق</button>
                    <button type="button" onclick="nextStep(3)"
                        class="btn btn-primary-gis px-5 py-3 rounded-pill fw-bold h4">المرحلة التالية</button>
                </div>
            </section>

            {{-- المرحلة الثالثة: تفاصيل الخدمة --}}
            <section class="wizard-card p-5 shadow-lg d-none" id="step3">
                <h4 class="fw-bold border-bottom pb-3 mb-4 text-navy">الخطوة الثالثة: بيانات وتفاصيل الخدمة</h4>
                <div class="mb-4 p-3 bg-light rounded-3">
                    <label class="form-label fw-bold h5 mb-3 text-warning">بيان الخدمة*</label>
                    <select name="request_type" id="request_type" class="form-select shadow-sm border-0 fw-bold" required
                        onchange="toggleRequestNature(this.value)">
                        <option value="new">تسجيل جديد</option>
                        <option value="restudy">إعادة دراسة</option>
                        <option value="duplicate">استخراج بدل فاقد</option>
                    </select>
                </div>

                <div id="new-request-fields">
                    <div class="row g-3">
                        <p class="text-primary fw-bold"><i class="fas fa-info-circle ms-2"></i>
                            استكمال البيانات اللازمة للتقدم علي الخدمة
                            :</p>
                        @foreach ($service->dynamic_fields as $field)
                            <div class="col-md-6 mb-3">
                                <label class="form-label small fw-bold">{{ $field['label'] }} @if ($field['is_required'] ?? false)
                                        <span class="text-danger">*</span>
                                    @endif
                                </label>
                                @if ($field['type'] == 'text' || $field['type'] == 'number')
                                    <input type="{{ $field['type'] }}" name="form_data[{{ $field['name'] }}]"
                                        class="form-control shadow-sm dynamic-input" data-name="{{ $field['name'] }}"
                                        {{ $field['is_required'] ?? false ? 'required' : '' }}>
                                @elseif($field['type'] == 'file')
                                    <input type="file" name="form_data[{{ $field['name'] }}]" class="form-control"
                                        {{ $field['is_required'] ?? false ? 'required' : '' }}>
                                @elseif($field['type'] == 'select')
                                    <select name="form_data[{{ $field['name'] }}]" class="form-select shadow-sm"
                                        {{ $field['is_required'] ?? false ? 'required' : '' }}>
                                        <option value="">-- اختر --</option>
                                        @foreach (explode("\n", $field['options'] ?? '') as $opt)
                                            <option value="{{ trim($opt) }}">{{ trim($opt) }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="row g-3 d-none" id="restudy-fields-wrapper">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-navy">كود الشهادة السابقة*</label>
                        <input type="text" name="prev_code" class="form-control text-center shadow-sm">
                    </div>
                    <div class="col-md-6" id="restudy-reason-container">
                        <label class="form-label fw-bold text-navy">سبب طلب إعادة الدراسة</label>
                        <select name="reason_id" class="form-select shadow-sm">
                            <option value="">-- اختر السبب --</option>
                            @foreach ($reasons as $r)
                                <option value="{{ $r->id }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="mt-4">
                    <button type="button" onclick="prevStep(2)"
                        class="btn btn-secondary px-5 py-3 rounded-pill fw-bold h4">السابق</button>
                    <button type="button" onclick="nextStep(4)"
                        class="btn btn-primary-gis px-5 py-3 rounded-pill fw-bold h4">المرحلة الأخيرة</button>
                </div>
            </section>

            {{-- المرحلة الرابعة: الفاتورة --}}
            <section class="wizard-card p-5 shadow-lg d-none text-center" id="step4">
                <div class="mb-4"><i class="fas fa-file-invoice-dollar fa-4x text-success"></i>
                    <h4 class="fw-bold text-navy">إتمام وإرسال الطلب</h4>
                </div>

                @php
                    $base = (float) $service->base_price;
                    $tax = $service->has_vat ? $base * 0.14 : 0;
                    $total = $base + $tax + 15;
                @endphp

                <div class="checkout-summary-card mx-auto mb-5 shadow-lg border-0 rounded-4" style="max-width: 480px;">
                    <div class="summary-head bg-navy p-3 text-center rounded-top-4 border-bottom border-gold border-4">
                        <span class="text-gold small fw-bold">بيان الرسوم والمستحقات</span>
                    </div>
                    <div class="p-4 bg-white rounded-bottom-4">
                        <h6 class="text-muted small mb-3">المجموع الكلي المطلوب سداده إلكترونياً:</h6>
                        <div class="final-price-wrapper mb-2"
                            style="display: flex; justify-content: center; align-items: baseline; gap: 10px; color: #e1b12c;">
                            <span class="h4 m-0 fw-bold">ج.م</span>
                            <span class="total-digit fw-950" id="liveTotalDisplay"
                                style="font-size: 3.5rem;">{{ number_format($total, 2) }}</span>
                        </div>
                        <div class="breakdown-pills d-flex flex-wrap gap-2 justify-content-center border-top pt-4">
                            <div class="badge bg-light text-dark p-2 border">شامل الضريبة (14%)</div>
                            <div class="badge bg-light text-dark p-2 border">دمغة الشهداء</div>
                        </div>
                    </div>
                </div>

                <div class="agreements-box mx-auto mb-4" style="max-width: 600px;">
                    <div
                        class="form-check d-flex gap-3 align-items-center mb-3 text-end p-3 rounded-3 bg-light border transition-hover">
                        <input class="form-check-input" type="checkbox" id="agreePayment" required
                            style="width:22px; height:22px;">
                        <label class="form-check-label small fw-bold" for="agreePayment">أوافق على شروط السداد
                            الإلكتروني</label>
                    </div>
                    <div
                        class="form-check d-flex gap-3 align-items-center mb-4 text-end p-3 rounded-3 bg-light border transition-hover">
                        <input class="form-check-input" type="checkbox" id="agreeService" required
                            style="width:22px; height:22px;">
                        <label class="form-check-label small fw-bold" for="agreeService">أقر بصحة البيانات والضوابط
                            الفنية</label>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-3">
                    <button type="button" onclick="prevStep(3)"
                        class="btn btn-secondary px-5 py-3 rounded-pill fw-bold h4">تعديل بيانات</button>
                    <button type="submit" class="btn btn-success btn-lg px-5 shadow-lg fw-900 rounded-pill">تأكيد وإرسال
                        الطلب</button>
                </div>
            </section>
        </form>
    </div>

    @include('gis.apply.modals')
@endsection

@push('scripts')
    <script>
        const pricingConfig = {
            type: "{{ $service->pricing_type ?? 'fixed' }}",
            basePrice: {{ (float) $service->base_price }},
            settings: @json($service->pricing_settings ?? []),
            hasVat: {{ $service->has_vat ? 1 : 0 }},
            fixedFees: 15.00
        };

        function calculateGrandTotal() {
            let corePrice = pricingConfig.basePrice;
            const requestType = document.getElementById('request_type').value;

            if (requestType === 'new') {
                const inputs = {};
                document.querySelectorAll('.dynamic-input').forEach(i => {
                    inputs[i.dataset.name] = parseFloat(i.value) || 0;
                });

                const areaVar = pricingConfig.settings.variable || 'area_m2';
                const areaVal = inputs[areaVar] || 0;
                const pointsVal = inputs['points_count'] || 0;

                if (pricingConfig.type === 'formula') {
                    corePrice = (areaVal * (parseFloat(pricingConfig.settings.multiplier) || 0)) + pricingConfig.basePrice;
                } else if (pricingConfig.type === 'tiered') {
                    if (pricingConfig.settings.tiers) {
                        const sortedTiers = [...pricingConfig.settings.tiers].sort((a, b) => a.max - b.max);
                        let found = false;
                        for (let t of sortedTiers) {
                            if (areaVal <= t.max) {
                                corePrice = parseFloat(t.price);
                                found = true;
                                break;
                            }
                        }
                        if (!found && sortedTiers.length) corePrice = parseFloat(sortedTiers[sortedTiers.length - 1].price);
                    }
                    if (pricingConfig.settings.has_overflow && areaVal > pricingConfig.settings.overflow_threshold) {
                        let extra = Math.ceil((areaVal - pricingConfig.settings.overflow_threshold) / (pricingConfig
                            .settings.overflow_unit_size || 4200));
                        corePrice += (extra * parseFloat(pricingConfig.settings.overflow_price));
                    }
                    if (pricingConfig.settings.point_threshold && pointsVal > pricingConfig.settings.point_threshold) {
                        corePrice += (pointsVal - pricingConfig.settings.point_threshold) * parseFloat(pricingConfig
                            .settings.point_extra);
                    }
                }
            }

            const vat = pricingConfig.hasVat ? (corePrice * 0.14) : 0;
            const final = corePrice + vat + pricingConfig.fixedFees;
            document.getElementById('liveTotalDisplay').innerText = final.toLocaleString('en-US', {
                minimumFractionDigits: 2
            });
            console.log('Final Price:', final);
        }

        // مراقبة المدخلات
        document.getElementById('gisMainForm').addEventListener('input', (e) => {
            if (e.target.classList.contains('dynamic-input')) calculateGrandTotal();
        });
        document.getElementById('request_type').addEventListener('change', calculateGrandTotal);

        function nextStep(s) {
            document.querySelectorAll('section').forEach(sec => sec.classList.add('d-none'));
            document.getElementById('step' + s).classList.remove('d-none');
            document.querySelectorAll('.step-dot').forEach(d => d.classList.remove('step-active'));
            document.getElementById('s' + s + '-head').classList.add('step-active');
            calculateGrandTotal();
            window.scrollTo(0, 0);
        }

        function prevStep(s) {
            nextStep(s);
        }

        function toggleAgentInfo(v) {
            document.getElementById('agent-side').style.display = (v == 'agent' ? 'block' : 'none');
        }

        function toggleRequestNature(val) {
            document.getElementById('new-request-fields').classList.toggle('d-none', val !== 'new');
            document.getElementById('restudy-fields-wrapper').classList.toggle('d-none', val === 'new');
            document.getElementById('restudy-reason-container').classList.toggle('d-none', val === 'duplicate');
        }

        async function loadUnits(id) {
            const res = await fetch(`/gis-portal/api/markaz/${id}/units`);
            const data = await res.json();
            document.getElementById('unit_select').innerHTML = '<option value="">-- اختر الوحدة --</option>' +
                data.map(i => `<option value="${i.id}">${i.name}</option>`).join('');
        }

        async function loadVillages(id) {
            const res = await fetch(`/gis-portal/api/unit/${id}/villages`);
            const data = await res.json();
            document.getElementById('village_select').innerHTML = '<option value="">-- اختر القرية --</option>' +
                data.map(i => `<option value="${i.id}">${i.name} ${i.is_ezba ? '(عزبة)' : ''}</option>`).join('');
        }
    </script>
@endpush
