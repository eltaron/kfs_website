@extends('layouts.app')

@section('title', 'بوابة الموظف الرقمية - ERP')

@push('css')
    <style>
        .erp-container {
            min-height: 80vh;
            margin-top: 30px;
        }

        .erp-sidebar {
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05);
        }

        .sidebar-menu {
            list-style: none;
            padding: 0;
        }

        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: #4b6584;
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 5px;
            transition: 0.3s;
        }

        .sidebar-menu li a:hover,
        .sidebar-menu li a.active {
            background: #e1b12c;
            color: #fff;
        }

        .sidebar-menu li a i {
            margin-left: 10px;
            font-size: 1.1rem;
        }

        .erp-main-card {
            background: linear-gradient(135deg, #1e272e 0%, #2f3542 100%);
            color: #fff;
            border-radius: 20px;
            padding: 40px;
            margin-bottom: 30px;
            position: relative;
            overflow: hidden;
        }

        .employee-welcome h2 {
            font-weight: 800;
        }

        .employee-badge {
            background: #e1b12c;
            color: #1e272e;
            padding: 5px 15px;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: bold;
        }

        .erp-stat-card {
            background: #fff;
            border-radius: 15px;
            padding: 20px;
            border-right: 5px solid #e1b12c;
        }

        .stat-val {
            font-size: 1.8rem;
            font-weight: 800;
            color: #1e272e;
        }

        .stat-label {
            color: #718093;
            font-size: 0.9rem;
        }
    </style>
@endpush

@section('content')
    <div class="container erp-container mb-5">
        <div class="row g-4">
            <!-- Sidebar -->
            <div class="col-lg-12">
                <!-- Welcome Section -->
                <div class="erp-main-card">
                    <div class="employee-welcome">
                        <span class="employee-badge mb-2">منظومة ERP محافظة كفر الشيخ</span>
                        <h2 class="mt-3">مرحباً بك مجدداً، {{ explode(' ', auth()->user()->name)[0] }}</h2>
                        <p class="mb-0 opacity-75">لديك {{ $stats['tasks_count'] }} مهام جديدة تتطلب انتباهك اليوم. نتمنى
                            لك
                            يوماً عملياً موفقاً.</p>
                    </div>
                </div>

                <!-- Placeholder for upcoming table (Transactions) -->
                <div class="mt-5 p-5 border-dashed text-center rounded-3 bg-white shadow-sm" style="border: 2px dashed #eee;">
                    <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">نظام إدارة المعاملات قيد التطوير</h5>
                    <p class="text-muted small">هذا القسم سيقوم بربط معاملات المواطنين بالمكتب الميداني التابع لك
                        قريباً.
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection
