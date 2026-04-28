<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceSubmission;
use Illuminate\Http\Request;
use App\Services\EFinanceService;

class ServiceSubmissionController extends Controller
{
    public function store(Request $request, Service $service, EFinanceService $efinance)
    {
        $rules = [];
        foreach ($service->form_fields ?? [] as $field) {
            if ($field['is_required'] ?? false) {
                $rules['form_data.' . $field['name']] = 'required';
            }
        }
        $validated = $request->validate($rules);
        $base = (float)$service->base_price;
        $extraFromInputs = 0;
        $formData = $request->input('form_data', []);
        if (isset($formData['area_m2'])) {
            $multiplier = 5; // أو اسحبه من $service->pricing_settings
            $extraFromInputs += (float)$formData['area_m2'] * $multiplier;
        }
        $subTotal = $base + $extraFromInputs;
        $tax = $service->has_vat ? ($subTotal * 0.14) : 0;
        $totalAmount = $subTotal + $tax + 5.00 + 10.00; // + الشهداء والـ SMS
        $submission = ServiceSubmission::create([
            'service_id'     => $service->id,
            'user_id'        => auth()->id(),
            'submitted_data' => $formData,
            'status'         => 'awaiting_payment',
            'total_amount'   => $totalAmount,
        ]);
        // $params = array(
        //     "sender_id"         => config('services.efinance.sender_id'),
        //     "sender_name"       => config('services.efinance.sender_name'),
        //     "efinance_password" => config('services.efinance.password'),
        //     "service_code"      => config('services.efinance.service_code'),
        //     "account_code"      => config('services.efinance.settlement_code'),
        //     "account_amount"    => number_format($totalAmount, 2, '.', ''), // المبلغ الحقيقي محسوباً
        //     "payment_gateway_url" => config('services.efinance.url'),
        //     "confirmation_url"  => route('efinance.callback'),
        //     "confirmation_redirect_url" => route('services.success'),
        //     "server_ip"         => $request->ip() === '127.0.0.1' ? '::1' : $request->ip(),
        //     "certificate_path"  => storage_path('app/efinance/InternetPaymentCrt.cer'),
        //     "client_order_id"   => (string)$submission->id // معرف الطلب الحقيقي من قاعدة بياناتك
        // );
        $params = array(
            "sender_id"         => "5057",
            "sender_name"       => 'محافظة كفر الشيخ',
            "efinance_password" => "1234",
            "service_code"      => "05057",
            "account_code"      => "5066",
            "account_amount"    => number_format($totalAmount, 2, '.', ''), // المبلغ الحقيقي محسوباً
            "payment_gateway_url" => 'https://test-payment.efinance.com.eg/CardPaymentRequestIntiation/index',
            "confirmation_url"  => route('efinance.callback'),
            "confirmation_redirect_url" => route('services.success'),
            "server_ip"         => $request->ip() === '127.0.0.1' ? '::1' : $request->ip(),
            "certificate_path"  => storage_path('app/efinance/InternetPaymentCrt.cer'),
            "client_order_id"   => (string)$submission->id // معرف الطلب الحقيقي من قاعدة بياناتك
        );
        $mechanism = array(
            "type"          => "NotSet",
            "mechanismType" => "NotSet",
            "channel"       => "",
        );
        try {
            $gatewayParams = $efinance->initiatePaymentRequest($params, $mechanism);

            return view('services.efinance_redirect', [
                'url'    => $params['payment_gateway_url'],
                'params' => $gatewayParams
            ]);
        } catch (\Exception $e) {
            $submission->update(['status' => 'failed']);
            return back()->with('error', 'حدث خطأ أثناء معالجة عملية الدفع: ' . $e->getMessage());
        }
    }
    public function paymentConfirmation(Request $request)
    {
        // هنا يجب فك تشفير الـ RequestObject القادم من e-finance
        // والتحقق من الـ SenderRequestNumber الذي أرسلناه (submission id)

        $submissionId = $request->SenderRequestNumber;
        $submission = ServiceSubmission::find($submissionId);

        if ($submission && $request->ResponseCode == '000') {
            $submission->update(['status' => 'paid']);
            return response('Success', 200); // إفادة بوابة الدفع بالاستلام صـ 15
        }

        return response('Failed', 400);
    }
}
