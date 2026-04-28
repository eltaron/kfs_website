@extends('layouts.app')
@section('title', 'تقديم بلاغ طارئ')
@push('css')
    <link rel="stylesheet" href="{{ asset('css') }}/survey.css">
@endpush
@section('content')
    <main class="main-content bg-light">

        <header class="page-header" style="background-image: url('{{ asset('images/bg/sytra.jpg') }}');">
            <div class="container text-center">
                <h1>مركز سيطرة الشبكة الوطنية للطوارئ</h1>
            </div>
        </header>
        <div class="container py-5">
            <form action="{{ route('emergency.store') }}" method="POST" enctype="multipart/form-data" class="survey-form">
                @csrf

                {{-- Hidden fields for Geolocation --}}
                <input type="hidden" name="latitude" id="latitude">
                <input type="hidden" name="longitude" id="longitude">

                {{-- Card 1: Personal Info --}}
                <div class="survey-card">
                    <h4 class="survey-group-title">بيانات شخصية</h4>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="reporter_name" class="form-label">مقدم البلاغ <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="reporter_name" name="reporter_name" class="form-control"
                                value="{{ old('reporter_name') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="reporter_phone" class="form-label">رقم التليفون <span
                                    class="text-danger">*</span></label>
                            <input type="tel" id="reporter_phone" name="reporter_phone" class="form-control"
                                value="{{ old('reporter_phone') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label for="reporter_national_id" class="form-label">الرقم القومي (14 رقم) <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="reporter_national_id" name="reporter_national_id" class="form-control"
                                pattern="\d{14}" title="يجب إدخال 14 رقمًا" value="{{ old('reporter_national_id') }}"
                                required>
                        </div>
                        <div class="col-md-6">
                            <label for="report_type" class="form-label">نوع البلاغ <span
                                    class="text-danger">*</span></label>
                            <select class="form-select" id="report_type" name="report_type" required>
                                <option value="" disabled selected>-- اختر نوع البلاغ --</option>
                                @isset($reportTypes)
                                    @foreach ($reportTypes as $type)
                                        <option value="{{ $type }}" {{ old('report_type') == $type ? 'selected' : '' }}>
                                            {{ $type }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Card 2: Location Info --}}
                <div class="survey-card">
                    <h4 class="survey-group-title">بيانات مكان الحادث</h4>
                    <div class="row g-3">
                        <div class="col-12 mb-2">
                            <label class="form-label d-block">النوع <span class="text-danger">*</span></label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="location_type" id="loc_type_city"
                                    value="مدينة" {{ old('location_type', 'مدينة') == 'مدينة' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="loc_type_city">مدينة</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="location_type" id="loc_type_village"
                                    value="قرية" {{ old('location_type') == 'قرية' ? 'checked' : '' }} required>
                                <label class="form-check-label" for="loc_type_village">قرية</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="center" class="form-label">المركز <span class="text-danger">*</span></label>
                            <select class="form-select" id="center" name="center" required>
                                <option value="" disabled selected>-- اختر المركز --</option>
                                @isset($centers)
                                    @foreach ($centers as $center_name)
                                        <option value="{{ $center_name }}"
                                            {{ old('center') == $center_name ? 'selected' : '' }}>{{ $center_name }}</option>
                                    @endforeach
                                @endisset
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="area" class="form-label">القرية / المدينة <span
                                    class="text-danger">*</span></label>
                            <input type="text" id="area" name="area" class="form-control"
                                value="{{ old('area') }}" placeholder="ادخل اسم القرية أو المدينة" required>
                        </div>
                        <div class="col-12">
                            <label for="location_description" class="form-label">مكان الحادث (وصف تفصيلي) <span
                                    class="text-danger">*</span></label>
                            <textarea id="location_description" name="location_description" class="form-control" rows="4"
                                placeholder="مثال: بجوار مدرسة X، أمام مبنى Y..." required>{{ old('location_description') }}</textarea>
                        </div>
                    </div>
                    <button type="button" id="getLocationBtn" class="btn btn-secondary mt-3">
                        <i class="fas fa-map-marker-alt"></i> تحديد موقعي الحالي تلقائيًا
                    </button>
                </div>
                {{-- Card 3: Details --}}
                <div class="survey-card">
                    <h4 class="survey-group-title">تفاصيل إضافية</h4>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label class="form-label">بيان تفصيلي للبلاغ</label>
                            <textarea name="details" class="form-control" rows="5"></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">إرفاق ملفات (صور، فيديو...)</label>
                            <input type="file" name="attachments[]" class="form-control" multiple>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-danger btn-lg px-5">إرسال البلاغ فورًا</button>
                </div>
            </form>
        </div>
    </main>
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const getLocationBtn = document.getElementById('getLocationBtn');
            const latInput = document.getElementById('latitude');
            const lonInput = document.getElementById('longitude');

            if (getLocationBtn && navigator.geolocation) {
                getLocationBtn.addEventListener('click', () => {
                    getLocationBtn.disabled = true;
                    getLocationBtn.innerHTML =
                        '<i class="fas fa-spinner fa-spin"></i> جاري تحديد الموقع...';

                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const latitude = position.coords.latitude;
                            const longitude = position.coords.longitude;

                            latInput.value = latitude;
                            lonInput.value = longitude;

                            getLocationBtn.innerHTML =
                                '<i class="fas fa-check-circle"></i> تم تحديد الموقع بنجاح!';
                            getLocationBtn.classList.remove('btn-secondary');
                            getLocationBtn.classList.add('btn-success');

                            // Optional: You can fill the textarea with a link to Google Maps
                            const locationTextarea = document.querySelector(
                                'textarea[name="location_description"]');
                            if (locationTextarea) {
                                locationTextarea.value +=
                                    `\n\nالموقع على الخريطة: https://maps.google.com/?q=${latitude},${longitude}`;
                            }
                        },
                        (error) => {
                            alert(
                                'عذرًا، لم نتمكن من الوصول لموقعك. يرجى التأكد من تفعيل خدمات الموقع في متصفحك أو قم بإدخال العنوان يدويًا.'
                            );
                            getLocationBtn.disabled = false;
                            getLocationBtn.innerHTML =
                                '<i class="fas fa-map-marker-alt"></i> تحديد موقعي الحالي تلقائيًا';
                        }
                    );
                });
            }
        });
    </script>
@endpush
