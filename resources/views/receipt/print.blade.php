<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <title>إيصال معاملة رقم #{{ $transaction->id }}</title>
    <style>
        /* Minimal CSS for printing */
        body {
            font-family: 'Cairo', sans-serif;
        }

        .receipt-container {
            width: 80mm;
            margin: auto;
            padding: 10px;
            border: 1px solid #ccc;
        }

        h1 {
            text-align: center;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 5px;
            border-bottom: 1px dashed #ddd;
        }
    </style>
</head>

<body onload="window.print()"> {{-- Automatically triggers print dialog --}}
    <div class="receipt-container">
        <img src="{{-- site logo --}}" alt="Logo" style="display:block; margin:auto; width:100px;">
        <h1>إيصال معاملة مالية</h1>
        <table>
            <tr>
                <td>رقم المعاملة:</td>
                <td>#{{ Str::limit($transaction->id, 8, '') }}</td>
            </tr>
            <tr>
                <td>التاريخ:</td>
                <td>{{ $transaction->completed_at->format('Y-m-d H:i') }}</td>
            </tr>
            <tr>
                <td>اسم العميل:</td>
                <td>{{ $transaction->user->name }}</td>
            </tr>
            <tr>
                <td>الخدمة:</td>
                <td>{{ $transaction->transactionable->service->title ?? $transaction->transactionable->trainingProgram->title }}
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <hr>
                </td>
            </tr>
            <tr>
                <td>المبلغ:</td>
                <td><strong>{{ number_format($transaction->amount, 2) }} جنيه</strong></td>
            </tr>
            <tr>
                <td>الحالة:</td>
                <td>مدفوع</td>
            </tr>
        </table>
        <p style="text-align:center; margin-top: 20px;">شكرًا لتعاملكم معنا.</p>
    </div>
</body>

</html>
