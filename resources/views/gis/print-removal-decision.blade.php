<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>مشروع قرار إزالة رقم {{ $record->stop_order_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap');

        @page {
            size: A4;
            margin: 0;
        }

        body {
            font-family: 'Cairo', sans-serif;
            padding: 40px;
            line-height: 1.8;
            color: #000;
            background: #fff;
        }

        .official-doc {
            border: 4px double #000;
            padding: 30px;
            min-height: 1000px;
            position: relative;
        }

        /* الهيدر الرسمي */
        .doc-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            border-bottom: 2px solid #000;
            padding-bottom: 20px;
        }

        .gov-info {
            text-align: center;
            font-weight: bold;
        }

        .logo {
            height: 100px;
        }

        .title-box {
            text-align: center;
            margin: 30px 0;
        }

        .title-box h2 {
            text-decoration: underline;
            font-size: 24px;
            font-weight: 900;
        }

        .content-section {
            margin-bottom: 20px;
            text-align: justify;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        .data-table td,
        .data-table th {
            border: 1px solid #000;
            padding: 10px;
        }

        .label-cell {
            background: #f2f2f2;
            font-weight: bold;
            width: 25%;
        }

        .signatures {
            margin-top: 20px;
            display: flex;
            justify-content: space-around;
            text-align: center;
        }

        .sig-box {
            width: 200px;
        }

        @media print {
            .no-print {
                display: none;
            }

            body {
                padding: 0;
            }

            .official-doc {
                border: none;
            }
        }
    </style>
</head>

<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()"
            style="padding: 10px 40px; background: #1e272e; color: #fff; border:none; cursor:pointer; font-weight:bold; border-radius:5px;">طباعة
            مشروع القرار 🖨️</button>
    </div>

    <div class="official-doc">
        <div class="doc-header">
            <div class="gov-info">
                جمهورية مصر العربية<br>
                محافظة كفر الشيخ<br>
                مركز المعلومات والتحول الرقمي
            </div>
            <img src="{{ asset('logo.png') }}" class="logo">
            <div class="gov-info">
                إدارة حوكمة العمران<br>
                قسم الإزالات والمخالفات<br>
                رقم الصادر: .................
            </div>
        </div>

        <div class="title-box">
            <h2>مشروع قرار إزالة إداري</h2>
            <p>بشأن المخالفة رقم ({{ $record->violation_report_number }}) لسنة {{ $record->created_at->format('Y') }}
            </p>
        </div>

        <div class="content-section">
            بناءً على المعاينة الميدانية والرفع المساحي والرصد الجيومكاني المعتمد من مركز المتغيرات المكانية بالمحافظة،
            تَبين قيام السيد/ <strong>{{ $record->owner_name }}</strong> بالمخالفة الموضحة أدناه:
        </div>

        <table class="data-table">
            <tr>
                <td class="label-cell">موقع المخالفة</td>
                <td>{{ $record->center }} - {{ $record->local_unit }} - شارع {{ $record->street }}</td>
            </tr>
            <tr>
                <td class="label-cell">نوع المخالفة</td>
                <td>{{ $record->violation_type === 'new_violation' ? 'بناء بدون ترخيص (مخالفة كلية)' : 'تجاوز شروط الترخيص' }}
                </td>
            </tr>
            <tr>
                <td class="label-cell">وصف الأعمال</td>
                <td>{{ $record->violation_works }}</td>
            </tr>
            <tr>
                <td class="label-cell">الأبعاد والمساحة</td>
                <td>{{ $record->violation_dimensions }} م</td>
            </tr>
            <tr>
                <td class="label-cell">التكلفة التقديرية</td>
                <td>{{ number_format($record->violation_cost, 2) }} جنيهاً مصرياً</td>
            </tr>
        </table>

        <div class="content-section">
            <strong>قررنا الآتي:</strong><br>
            مادة (1): تُزال بالطريق الإداري كافة الأعمال المخالفة الموضحة عاليه على نفقة المخالف.<br>
            مادة (2): على كافة الجهات المختصة (الوحدة المحلية والشرطة) تنفيذ هذا القرار فور صدوره.<br>
            مادة (3): يُنشر هذا القرار ويُخطر به ذوي الشأن طبقاً للقانون.
        </div>

        <div class="signatures">
            <div class="sig-box">
                مدير الإدارة الهندسية<br><br>...........................
            </div>
            <div class="sig-box">
                رئيس المركز والمدينة<br><br>...........................
            </div>
            <div class="sig-box">
                يُعتمد،، محافظ كفر الشيخ<br><br>...........................
            </div>
        </div>

        <div style="position: absolute; bottom: 20px; left: 20px; font-size: 10px; color: #888;">
            تم استخراج المسودة آلياً عبر نظام حوكمة الإزالات الرقمي - Ticket ID: {{ $record->id }}
        </div>
    </div>
</body>

</html>
