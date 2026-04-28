@extends('layouts.app')
@section('title', 'لوحة التحكم')
@push('css')
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <style>
        body {
            padding: 0;
        }

        header {
            display: none;
        }

        /* تنسيق الجداول والحالات للخدمات */
        .custom-dash-table {
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .custom-dash-table thead th {
            border: none;
            font-size: 0.85rem;
            color: #8492a6;
        }

        .custom-dash-table tbody tr {
            transition: 0.3s;
            background: #fdfdfd;
        }

        .custom-dash-table tbody tr td {
            border-top: 1px solid #eee;
            border-bottom: 1px solid #eee;
            padding: 15px 10px;
        }

        .custom-dash-table tbody tr td:first-child {
            border-right: 1px solid #eee;
            border-radius: 0 10px 10px 0;
        }

        .custom-dash-table tbody tr td:last-child {
            border-left: 1px solid #eee;
            border-radius: 10px 0 0 10px;
        }

        .icon-sm-circle {
            width: 35px;
            height: 35px;
            background: rgba(30, 39, 46, 0.05);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--dash-navy);
        }

        /* حالات الطلبات الإضافية */
        .badge-status-awaiting_payment {
            background: rgba(231, 76, 60, 0.1);
            color: #e74c3c;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
        }

        .badge-status-paid {
            background: rgba(46, 204, 113, 0.1);
            color: #27ae60;
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
        }

        .badge-status-completed {
            background: var(--dash-navy);
            color: var(--dash-gold);
            padding: 4px 12px;
            border-radius: 50px;
            font-size: 0.75rem;
        }

        .search-inquiry-box {
            min-width: 250px;
        }

        .btn-outline-gold {
            border: 1px solid var(--dash-gold);
            color: var(--dash-gold);
        }

        .btn-outline-gold:hover {
            background: var(--dash-gold);
            color: #fff;
        }
    </style>
@endpush

@section('content')
    <div class="dashboard-root">
        <div class="container-fluid g-0">
            <div class="dashboard-layout">
                {{-- ===================== Main Body ===================== --}}
                <main class="dashboard-content-area">
                    {{-- هيدر علوي حديث --}}
                    <div class="top-nav-dash bg-white shadow-sm mb-4">
                        <div class="d-flex justify-content-between align-items-center w-100 px-4 py-3">
                            <div class="brand-box d-flex align-items-center">
                                <img src="{{ Storage::url($settings['site_logo_header'] ?? '') }}" height="40"
                                    class="me-2" alt="logo">
                            </div>
                            <div class="d-flex align-items-center gap-3">
                                @if (auth()->user()->isEmployee())
                                    <a href="{{ route('employee.erp.index') }}"
                                        class="btn btn-dark btn-sm rounded-pill px-3">
                                        <i class="fas fa-laptop-code me-1"></i> بوابة الموظفين
                                    </a>
                                @endif
                                <a class="logout-dash-btn" href="{{ route('logout') }}"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-power-off"></i> خروج
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="px-4">
                        <h2 class="page-title mb-4">أهلاً بك، {{ explode(' ', $user->name)[0] }} </h2>

                        {{-- كروت الملخص --}}
                        <div class="row g-3 mb-5">
                            <div class="col-md-6">
                                <div class="stat-card gold shadow-sm">
                                    <div class="card-inner">
                                        <div class="info">
                                            <h3>{{ $complaints->count() }}</h3>
                                            <p>إجمالي الشكاوى</p>
                                        </div>
                                        <i class="fas fa-clipboard-list icon-bg"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="stat-card blue shadow-sm">
                                    <div class="card-inner">
                                        <div class="info">
                                            <h3>{{ $suggestions->count() }}</h3>
                                            <p>مقترحات مقدمة</p>
                                        </div>
                                        <i class="fas fa-hand-holding-heart icon-bg"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- قسم الشكاوى (الأكورديون الأنيق) --}}
                        <div class="white-section-card shadow-sm p-4 mb-5">
                            <div class="d-flex align-items-center mb-4 border-bottom pb-2">
                                <i class="fas fa-comments-alt text-primary fs-4 me-2"></i>
                                <h3 class="m-0 section-head">سجل الشكاوى والطلبات</h3>
                            </div>

                            @forelse($complaints as $complaint)
                                <div class="dash-accordion-item mb-2">
                                    <button class="dash-accordion-header collapsed" data-bs-toggle="collapse"
                                        data-bs-target="#comp-{{ $complaint->id }}">
                                        <div class="title-side">
                                            <span class="status-indicator {{ $complaint->status }}"></span>
                                            <h6>{{ $complaint->subject }}</h6>
                                        </div>
                                        <div class="meta-side">
                                            <span class="time-text">{{ $complaint->created_at->diffForHumans() }}</span>
                                            <span class="badge-status-{{ $complaint->status }}">
                                                @lang("statuses.complaints.{$complaint->status}")
                                            </span>
                                            <i class="fas fa-chevron-down arrow-ico"></i>
                                        </div>
                                    </button>
                                    <div id="comp-{{ $complaint->id }}" class="collapse">
                                        <div class="p-4 border-top">
                                            <div class="msg-box user">
                                                <label>رسالتك:</label>
                                                <p>{{ $complaint->message }}</p>
                                            </div>
                                            @if ($complaint->admin_reply)
                                                <div class="msg-box admin">
                                                    <label>الرد الرسمي:</label>
                                                    <div class="reply-content">{!! $complaint->admin_reply !!}</div>
                                                </div>
                                            @else
                                                <p class="small text-muted mt-3 italic"><i
                                                        class="fas fa-hourglass-half me-1"></i> بانتظار المراجعة من الجهة
                                                    المختصة...</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-state text-center py-5">
                                    <i class="fas fa-folder-open fa-3x text-light"></i>
                                    <p class="mt-2 text-muted">لا يوجد سجلات للشكاوى حالياً</p>
                                </div>
                            @endforelse
                        </div>
                        {{-- ===================== سكشن تتبع وطلبات الخدمات المساحية ===================== --}}
                        <div class="white-section-card shadow-sm p-4 mb-5" id="services-section">
                            <div class="d-md-flex align-items-center justify-content-between mb-4 border-bottom pb-3">
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-file-invoice-dollar text-warning fs-4 ms-2"></i>
                                    <h3 class="m-0 section-head">سجل طلبات الخدمات </h3>
                                </div>

                                {{-- مربع البحث برقم الطلب --}}
                                <div class="search-inquiry-box mt-3 mt-md-0">
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text bg-light border-0"><i
                                                class="fas fa-search text-muted"></i></span>
                                        <input type="text" id="orderSearchInput" class="form-control border-0 bg-light"
                                            placeholder="ابحث برقم الطلب..." onkeyup="filterOrders()">
                                    </div>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-hover align-middle custom-dash-table" id="ordersTable">
                                    <thead class="bg-light">
                                        <tr>
                                            <th>رقم الطلب</th>
                                            <th>نوع الخدمة</th>
                                            <th>تاريخ التقديم</th>
                                            <th>حالة الطلب</th>
                                            <th class="text-center">إجراءات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($serviceSubmissions as $submission)
                                            <tr class="order-row">
                                                <td><span class="fw-bold text-navy">#{{ $submission->id }}</span></td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="icon-sm-circle ms-2"><i
                                                                class="{{ $submission->service->icon }}"></i></div>
                                                        <span>{{ $submission->service->title }}</span>
                                                    </div>
                                                </td>
                                                <td><span
                                                        class="text-muted small">{{ $submission->created_at->format('Y/m/d') }}</span>
                                                </td>
                                                <td>
                                                    <span class="badge-status-{{ $submission->status }}">
                                                        {{-- تحويل الحالة لنص عربي --}}
                                                        @switch($submission->status)
                                                            @case('pending')
                                                                قيد المراجعة
                                                            @break

                                                            @case('awaiting_payment')
                                                                بانتظار الدفع
                                                            @break

                                                            @case('paid')
                                                                تم الدفع
                                                            @break

                                                            @case('completed')
                                                                تم التنفيذ
                                                            @break

                                                            @case('rejected')
                                                                مرفوض
                                                            @break

                                                            @default
                                                                {{ $submission->status }}
                                                        @endswitch
                                                    </span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="{{ route('citizen.submissions.show', $submission->id) }}"
                                                        class="btn btn-outline-gold btn-sm rounded-pill d-inline-flex align-items-center justify-content-center">
                                                        <span>التفاصيل</span>
                                                        <i class="fas fa-eye fa-xs ms-2"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="text-center py-5">
                                                        <div class="text-muted">
                                                            <i class="fas fa-inbox fa-3x mb-3 opacity-25"></i>
                                                            <p>لم تقم بالتقديم على أي خدمات إلكترونية حتى الآن</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </main>
                    {{-- ===================== Sidebar ===================== --}}
                    <aside class="dashboard-sidebar shadow-sm">
                        <div class="sidebar-sticky-top">
                            <div class="user-profile-section">
                                <div class="avatar-box">
                                    <i class="fas fa-user-circle"></i>
                                    <span class="online-indicator"></span>
                                </div>
                                <h5 class="user-name">{{ $user->name }}</h5>
                                <p class="user-role">مواطن مفعل</p>
                            </div>

                            <nav class="sidebar-nav-custom">
                                <div class="nav-group">
                                    <label>خدمات التقديم</label>
                                    <a href="{{ route('complaints.create') }}" class="nav-item">
                                        <i class="fas fa-edit"></i> <span>تقديم شكوى</span>
                                    </a>
                                    <a href="{{ route('suggestions.create') }}" class="nav-item">
                                        <i class="fas fa-lightbulb"></i> <span>إضافة مقترح</span>
                                    </a>
                                    <a href="{{ route('surveys.service.create') }}" class="nav-item">
                                        <i class="fas fa-award"></i> <span>تقييم الأداء</span>
                                    </a>
                                    <a href="{{ route('emergency.create') }}" class="nav-item">
                                        <i class="fas fa-shield-alt text-danger"></i> <span>بلاغ طوارئ</span>
                                    </a>
                                </div>

                                <div class="nav-group mt-4">
                                    <label>أدوات الاستكشاف</label>
                                    <a href="{{ route('services.index') }}" class="nav-item">
                                        <i class="fas fa-briefcase"></i> <span>دليل الخدمات</span>
                                    </a>
                                    <a href="{{ route('directory.index') }}" class="nav-item">
                                        <i class="fas fa-map-marked-alt"></i> <span>دليل المناطق</span>
                                    </a>
                                </div>
                            </nav>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    @endsection
    @push('scripts')
        <script>
            function filterOrders() {
                let input = document.getElementById('orderSearchInput');
                let filter = input.value.toLowerCase();
                let rows = document.querySelectorAll('.order-row');

                rows.forEach(row => {
                    let orderID = row.querySelector('td:first-child').innerText.toLowerCase();
                    if (orderID.includes(filter)) {
                        row.style.display = "";
                    } else {
                        row.style.display = "none";
                    }
                });
            }
        </script>
    @endpush
