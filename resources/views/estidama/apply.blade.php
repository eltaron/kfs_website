@extends('layouts.app')
@section('title', 'التقديم على برنامج: ' . $program->title)
@push('css')
    <link rel="stylesheet" href="{{ asset('css') }}/survey.css">
@endpush

@section('content')
    <main class="main-content bg-light">
        {{-- Page Header --}}
        <header class="page-header" style="background-image: url('{{ Storage::url($program->image) }}');">
            <div class="header-overlay"></div>
            <div class="container text-center">
                <span class="page-subtitle">تقديم طلب تسجيل</span>
                <h1>{{ $program->title }}</h1>
                <p>يرجى ملء النموذج التالي للتقديم على البرنامج التدريبي.</p>
            </div>
        </header>

        <div class="container py-5">
            <form action="{{ route('estidama.storeApplication', $program) }}" method="POST" enctype="multipart/form-data"
                class="survey-form">
                @csrf

                @if (session('success'))
                    <div class="alert alert-success fs-5 text-center my-4">{{ session('success') }}</div>
                @else
                    @if ($errors->any())
                        <div class="alert alert-danger mb-4">
                            <strong>خطأ!</strong> يرجى مراجعة الحقول وإصلاح الأخطاء.
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <p class="text-center mb-4"><strong>جميع الحقول التي تحتوي على هذه العلامة (<span
                                class="text-danger">*</span>) مطلوبة</strong></p>

                    <div class="survey-card">
                        {{-- All form fields from your 3rd image go here --}}
                        {{-- Personal Info --}}
                        <h4 class="survey-group-title">بيانات المتقدم</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="applicant_name" class="form-label">الاسم الكامل (رباعي) <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="applicant_name" name="applicant_name" class="form-control"
                                    value="{{ old('applicant_name') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="national_id" class="form-label">الرقم القومي (14 رقم) <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="national_id" name="national_id" class="form-control"
                                    pattern="\d{14}" title="يجب إدخال 14 رقمًا" value="{{ old('national_id') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">رقم الهاتف <span
                                        class="text-danger">*</span></label>
                                <input type="tel" id="phone" name="phone" class="form-control"
                                    value="{{ old('phone') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="applicant_email" class="form-label">البريد الإلكتروني <span
                                        class="text-danger">*</span></label>
                                <input type="email" id="applicant_email" name="applicant_email" class="form-control"
                                    value="{{ old('applicant_email') }}" required>
                            </div>
                            <div class="col-12">
                                <label class="form-label d-block">الجنس <span class="text-danger">*</span></label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="gender_male"
                                        value="ذكر" {{ old('gender') == 'ذكر' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="gender_male">ذكر</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="gender" id="gender_female"
                                        value="أنثى" {{ old('gender') == 'أنثى' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="gender_female">أنثى</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="educational_qualification" class="form-label">المؤهل الدراسي <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="educational_qualification" name="educational_qualification"
                                    class="form-control" value="{{ old('educational_qualification') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="specialization" class="form-label">التخصص الدراسي</label>
                                <input type="text" id="specialization" name="specialization" class="form-control"
                                    value="{{ old('specialization') }}">
                            </div>
                            <div class="col-md-12">
                                <label for="highest_degree" class="form-label">أعلى مؤهل دراسات عليا (إن وجد)</label>
                                <input type="text" id="highest_degree" name="highest_degree" class="form-control"
                                    value="{{ old('highest_degree') }}">
                            </div>
                        </div>
                    </div>

                    <div class="survey-card">
                        <h4 class="survey-group-title">معلومات العمل</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="employment_status" class="form-label">جهة العمل <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="employment_status" name="employment_status"
                                    class="form-control" value="{{ old('employment_status') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label for="current_position" class="form-label">الوظيفة الحالية <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="current_position" name="current_position" class="form-control"
                                    value="{{ old('current_position') }}" required>
                            </div>
                            <div class="col-12">
                                <label for="job_address" class="form-label">عنوان الوظيفة <span
                                        class="text-danger">*</span></label>
                                <input type="text" id="job_address" name="job_address" class="form-control"
                                    value="{{ old('job_address') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="survey-card">
                        <h4 class="survey-group-title">المرفقات</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="national_id_front_image" class="form-label">صورة البطاقة (الوجه الأمامي) <span
                                        class="text-danger">*</span></label>
                                <input type="file" id="national_id_front_image" name="national_id_front_image"
                                    class="form-control" required>
                                <small class="form-text text-muted">الحد الأقصى للحجم: 2MB.</small>
                                @error('national_id_front_image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="national_id_back_image" class="form-label">صورة البطاقة (الوجه الخلفي) <span
                                        class="text-danger">*</span></label>
                                <input type="file" id="national_id_back_image" name="national_id_back_image"
                                    class="form-control" required>
                                <small class="form-text text-muted">الحد الأقصى للحجم: 2MB.</small>
                                @error('national_id_back_image')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="personal_statement" class="form-label">بيان حالة مدون (اختياري)</label>
                                <input type="file" id="personal_statement" name="personal_statement"
                                    class="form-control">
                                <small class="form-text text-muted">النوع المسموح به: PDF. الحد الأقصى للحجم: 2MB.</small>
                                @error('personal_statement')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="survey-card">
                        <h4 class="survey-group-title">أسئلة إضافية</h4>
                        <div class="row">
                            <div class="col-12 survey-question">
                                <label class="question-text">هل حصلت على برامج تدريبية سابقة في مركز تدريب التنمية المحلية
                                    بسقارة؟ <span class="text-danger">*</span></label>
                                <div class="options-group-custom">
                                    <div class="option-item"><input class="form-check-input visually-hidden"
                                            type="radio" name="has_taken_previous_courses" id="prev_yes"
                                            value="1" required><label class="form-check-label"
                                            for="prev_yes">نعم</label></div>
                                    <div class="option-item"><input class="form-check-input visually-hidden"
                                            type="radio" name="has_taken_previous_courses" id="prev_no"
                                            value="0" required><label class="form-check-label"
                                            for="prev_no">لا</label></div>
                                </div>
                            </div>
                            <div class="col-12 mt-3" id="previousCoursesNamesWrapper" style="display:none;">
                                <label for="previous_courses_names" class="form-label">في حالة الإجابة بنعم، يرجى ذكر
                                    أسماء
                                    البرامج التدريبية</label>
                                <textarea name="previous_courses_names" id="previous_courses_names" class="form-control" rows="3">{{ old('previous_courses_names') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <button type="submit" class="btn btn-primary btn-lg px-5">إرسال الطلب</button>
                    </div>

                @endif
            </form>
        </div>
    </main>
@endsection

@push('scripts')
    <script>
        // Show/hide the "previous courses" textarea based on radio button selection
        document.querySelectorAll('input[name="has_taken_previous_courses"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.getElementById('previousCoursesNamesWrapper').style.display = this.value === '1' ?
                    'block' : 'none';
            });
        });
    </script>
@endpush
