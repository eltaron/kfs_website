<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServiceSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
        $request->validate($rules);

        $formData        = $request->input('form_data', []);
        $base            = (float) $service->base_price;
        $extraFromInputs = 0;

        if (isset($formData['area_m2'])) {
            $multiplier      = (float) ($service->pricing_settings['area_multiplier'] ?? 5);
            $extraFromInputs += (float) $formData['area_m2'] * $multiplier;
        }

        $subTotal    = $base + $extraFromInputs;
        $tax         = $service->has_vat ? ($subTotal * 0.14) : 0;
        $totalAmount = $subTotal + $tax + 5.00 + 10.00;

        $submission = ServiceSubmission::create([
            'service_id'     => $service->id,
            'user_id'        => auth()->id(),
            'submitted_data' => $formData,
            'status'         => 'awaiting_payment',
            'total_amount'   => $totalAmount,
        ]);

        $params = [
            'sender_id'                 => config('services.efinance.sender_id'),
            'sender_name'               => config('services.efinance.sender_name'),
            'efinance_password'         => config('services.efinance.password'),
            'service_code'              => config('services.efinance.service_code'),
            'account_code'              => config('services.efinance.settlement_code'),
            'account_amount'            => number_format($totalAmount, 2, '.', ''),
            'payment_gateway_url'       => config('services.efinance.url'),
            'confirmation_url'          => route('efinance.callback'),
            'confirmation_redirect_url' => route('services.success', ['submission' => $submission->id]),
            'server_ip'                 => $this->resolveClientIp($request),
            'certificate_path'          => storage_path('app/efinance/InternetPaymentCrt.cer'),
            'client_order_id'           => (string) $submission->id,
        ];

        $mechanism = [
            'type'          => 'NotSet',
            'mechanismType' => 'NotSet',
            'channel'       => '',
        ];

        try {
            $gatewayParams = $efinance->initiatePaymentRequest($params, $mechanism);
            return view('services.efinance_redirect', [
                'url'    => $params['payment_gateway_url'],
                'params' => $gatewayParams,
            ]);
        } catch (\Exception $e) {
            $submission->update(['status' => 'failed']);
            Log::error('EFinance initiation failed', [
                'submission_id' => $submission->id,
                'error'         => $e->getMessage(),
            ]);
            return back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // SILENT CALL
    //
    public function paymentConfirmation(Request $request, EFinanceService $efinance)
    {
        $senderRequestNumber = $request->input('SenderRequestNumber');
        $requestObject       = $request->input('RequestObject');

        Log::info('EFinance silent call received', [
            'SenderID'            => $request->input('SenderID'),
            'SenderRequestNumber' => $senderRequestNumber,
        ]);

        if (!$senderRequestNumber || !$requestObject) {
            Log::warning('EFinance: missing required POST fields');
            return $this->ack();
        }

        try {
            $submissionId = $efinance->resolveSubmissionId($senderRequestNumber);

            if (!$submissionId) {
                Log::error('EFinance: cannot map SenderRequestNumber', compact('senderRequestNumber'));
                return $this->ack();
            }

            $submission = ServiceSubmission::find((int) $submissionId);

            if (!$submission) {
                Log::error('EFinance: submission not found', ['submission_id' => $submissionId]);
                return $this->ack();
            }

            if ($submission->status === 'paid') {
                return $this->ack();
            }

            $decoded = $efinance->silentCall($requestObject, $senderRequestNumber);

            $paymentRequestNumber = $decoded['PaymentRequestNumber']      ?? null;
            $authorizationCode    = $decoded['AuthorizationCode']         ?? null;
            $transactionNumber    = $decoded['TransactionNumber']         ?? null;
            $totalAmount          = $decoded['PaymentRequestTotalAmount'] ?? null;

            Log::info('EFinance decrypted payload', [
                'submission_id'        => $submission->id,
                'PaymentRequestNumber' => $paymentRequestNumber,
                'AuthorizationCode'    => $authorizationCode,
                'TransactionNumber'    => $transactionNumber,
            ]);

            // ✅ AuthorizationCode هو مؤشر النجاح — لو موجود = الدفع تم
            if (!empty($authorizationCode)) {
                $submission->update([
                    'status'                 => 'paid',
                    'payment_request_number' => $paymentRequestNumber,
                    'authorization_code'     => $authorizationCode,
                    'transaction_number'     => $transactionNumber,
                    'paid_at'                => now(),
                ]);

                Log::info('EFinance: payment confirmed ✅', [
                    'submission_id'        => $submission->id,
                    'PaymentRequestNumber' => $paymentRequestNumber,
                    'AuthorizationCode'    => $authorizationCode,
                ]);
            } else {
                $submission->update(['status' => 'payment_failed']);
                Log::warning('EFinance: no AuthorizationCode — payment failed ❌', [
                    'submission_id' => $submission->id,
                ]);
            }

        } catch (\Exception $e) {
            Log::error('EFinance silent call exception', [
                'SenderRequestNumber' => $senderRequestNumber,
                'error'               => $e->getMessage(),
            ]);
        }

        return $this->ack();
    }
    /**
     * ACK لـ e-finance — HTTP 200 + Response_Code: 000 header (underscore)
     */
    private function ack(): \Illuminate\Http\Response
    {
        return response('', 200)->header('Response_Code', '000');
    }

    private function resolveClientIp(Request $request): string
    {
        $ip = $request->ip();
        return in_array($ip, ['127.0.0.1', '::1'], true) ? '::1' : $ip;
    }
}