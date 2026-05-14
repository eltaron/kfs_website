<?php

namespace App\Http\Controllers;

use App\Models\GisMarkaz;
use App\Models\GisShiakha;
use App\Models\GisVillage;
use App\Models\GisSubService;
use App\Models\GisSubmission;
use App\Models\GisServiceType;
use App\Models\GisStudyReason;
use App\Services\ArcGisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Services\EFinanceService;

class GisPortalController extends Controller
{
    /**
     * 1. عرض الصفحة الرئيسية للبوابة
     */
    public function index()
    {
        $categories = GisServiceType::with('subServices')->orderBy('created_at')->get();
        return view('gis.index', compact('categories'));
    }

    /**
     * 2. صفحة تفاصيل الخدمة
     */
    public function showService($slug)
    {
        $service = GisSubService::where('slug', $slug)->with('serviceType')->firstOrFail();
        return view('gis.services.show', compact('service'));
    }

    /**
     * 3. API: جلب الوحدات المحلية
     */
    public function getLocalUnits($markazId)
    {
        $units = GisShiakha::where('gis_markaz_id', $markazId)->select('id', 'name')->get();
        return response()->json($units);
    }

    /**
     * 4. API: جلب القرى
     */
    public function getVillages($unitId)
    {
        $villages = GisVillage::where('gis_shiakha_id', $unitId)->select('id', 'name', 'is_ezba')->get();
        return response()->json($villages);
    }

    /**
     * 5. عرض صفحة التقديم (Wizard)
     */
    public function createApplication($slug)
    {
        $service = GisSubService::where('slug', $slug)->firstOrFail();
        $markazs = GisMarkaz::all();
        $reasons = GisStudyReason::all();

        return view('gis.apply.start', compact('service', 'markazs', 'reasons'));
    }

    /**
     * 6. معالجة وحفظ الطلب (تتضمن محرك حساب الأسعار)
     */
    public function storeApplication(Request $request, $slug, EFinanceService $efinance, ArcGisService $arcGis)
    {
        $service = GisSubService::where('slug', $slug)->firstOrFail();
        // أ- التحقق من البيانات الأساسية
        $rules = [
            'applicant_type' => 'required|in:owner,agent',
            'markaz_id' => 'required|exists:gis_markazs,id',
            'shiakha_id' => 'required|exists:gis_shiakhas,id',
            'village_id' => 'required|exists:gis_villages,id',
            'request_type' => 'required|in:new,restudy,duplicate',
            'owner_id_card' => 'required|file|image|max:2048',
        ];

        // ب- التحقق من الحقول الديناميكية (فقط في حالة "جديد")
        if ($request->request_type === 'new' && $service->dynamic_fields) {
            foreach ($service->dynamic_fields as $field) {
                if (isset($field['is_required']) && $field['is_required']) {
                    $rules['form_data.' . $field['name']] = 'required';
                }
            }
        }

        // $validator = \Illuminate\Support\Facades\Validator::make($request->all(), $rules);

        // if ($validator->fails()) {
        //     dd($validator->errors()->toArray()); // سيظهر لك اسم الحقل والخطأ بالتفصيل
        // }
        $validated = $request->validate($rules);

        // ج- رفع الملفات الأساسية
        $attachments = [
            'id_front' => $request->file('owner_id_card')->store('gis/submissions/ids', 'public'),
        ];

        if ($request->applicant_type === 'agent') {
            $request->validate(['proxy_doc' => 'required|file']);
            $attachments['proxy_doc'] = $request->file('proxy_doc')->store('gis/submissions/proxy', 'public');
        }

        // د- رفع ملفات الحقول الديناميكية
        $formData = $request->input('form_data', []);
        if ($request->hasFile('form_data')) {
            foreach ($request->file('form_data') as $key => $file) {
                $formData[$key] = $file->store('gis/submissions/fields', 'public');
            }
        }

        // هـ- حساب التكلفة المالية (The Pricing Engine)
        // نقوم بحساب السعر بناءً على المدخلات (مساحة/نقاط) ونوع تسعير الخدمة
        $calculatedTotalAmount = $this->calculateServiceCost($service, $formData);

        // و- حفظ المعاملة
        $submission = GisSubmission::create([
            'user_id' => auth()->id(),
            'gis_sub_service_id' => $service->id,
            'request_type' => $request->request_type,
            'status' => 'received',
            'payment_status' => 'pending', // بانتظار الدفع
            'total_amount' => $calculatedTotalAmount, // السعر النهائي المحسوب
            'applicant_info' => [
                'type' => $request->applicant_type,
                'name' => auth()->user()->name,
                'agent_name' => $request->input('agent_name'),
            ],
            'address_info' => [
                'markaz_id' => $request->markaz_id,
                'shiakha_id' => $request->shiakha_id,
                'village_id' => $request->village_id,
                'details' => $request->input('address')
            ],
            'form_data' => $formData,
            'attachments' => $attachments,
        ]);
        // 3. توليد باراميترات e-finance المشركة
        // $gatewayParams = $efinance->generatePaymentParams($submission, $calculatedTotalAmount);
        // يمكن هنا توجيه المستخدم لصفحة الدفع الإلكتروني (E-Finance) بدلاً من النجاح المباشر
        // return redirect()->route('efinance.redirect', $submission->id);

        $arcGisData = [
            'RequestNumber' => 'REQ-' . $submission->id,
            'Name' => auth()->user()->name,
            'NationalID' => $request->national_id, // تأكد من إضافته للـ Request
            'MobileNumber' => auth()->user()->phone,
            'Address' => $request->address,
            'Markaz_Name' => $request->markaz_id,
            'Servicetype' => $service->serviceType->name,
            'Subservicetype' => $service->name,
            'Cost' => $calculatedTotalAmount,
            'CreatedDate' => now()->format('Y-m-d H:i:s'),
            'IsPaid' => 0, // كما طلبت
        ];

        // إرسال البيانات
        try {
            $arcGis->syncToArcGis($arcGisData);
        } catch (\Exception $e) {
            dd('Error syncing to ArcGIS: ' . $e->getMessage());
            \Log::error('ArcGIS Sync Error: ' . $e->getMessage());
        }


        return redirect()->route('gis.apply.success', $submission->id);
    }

    /**
     * دالة خاصة لحساب سعر الخدمة بناءً على المعادلات (Pricing Engine)
     */
    private function calculateServiceCost(GisSubService $service, array $formData): float
    {
        $price = 0;
        $settings = $service->pricing_settings ?? []; // الإعدادات المخزنة في الداتابيز
        $type = $service->pricing_type ?? 'fixed';    // نوع التسعير (fixed, formula, tiered)

        // 1. حساب السعر الأساسي للخدمة (Net Price)
        if ($type === 'fixed') {
            // سعر ثابت بسيط
            $price = $service->base_price;
        } elseif ($type === 'formula') {
            // معادلة خطية: (القيمة المدخلة * المعامل) + مبلغ أساسي
            // مثال: الميزانية الشبكية = (المساحة * 5) + 600
            $variableName = $settings['variable'] ?? 'area_m2'; // اسم الحقل المتغير (مساحة)
            $inputVal = isset($formData[$variableName]) ? (float)$formData[$variableName] : 0;

            $multiplier = (float)($settings['multiplier'] ?? 1);
            $fixedExtra = (float)($settings['fixed_extra'] ?? 0);

            $price = ($inputVal * $multiplier) + $fixedExtra;

            // معادلات خاصة (مثل الترافرس: نقاط + مساحة)
            if (isset($settings['static_point_price']) && isset($formData['points_static_count'])) {
                $points = (int)$formData['points_static_count'];
                $price += ($points * $settings['static_point_price']);
            }
        } elseif ($type === 'tiered') {
            // نظام الشرائح: (مثل توقيع الإحداثيات)
            $variableName = $settings['variable'] ?? 'area_m2';
            $inputVal = isset($formData[$variableName]) ? (float)$formData[$variableName] : 0;

            $price = 0;
            // البحث في الشرائح لمعرفة السعر المناسب للمساحة
            if (isset($settings['tiers']) && is_array($settings['tiers'])) {
                foreach ($settings['tiers'] as $tier) {
                    if ($inputVal <= $tier['max']) {
                        $price = $tier['price'];
                        break;
                    }
                }
                // إذا تجاوزت كل الشرائح، نأخذ سعر آخر شريحة
                if ($price == 0 && count($settings['tiers']) > 0) {
                    $price = end($settings['tiers'])['price'];
                }
            }

            // إضافة تكلفة النقاط الزائدة (إن وجدت)
            if (isset($settings['point_threshold']) && isset($formData['points_count'])) {
                $points = (int)$formData['points_count'];
                $threshold = (int)$settings['point_threshold'];
                $extraCost = (float)($settings['point_extra'] ?? 0);

                if ($points > $threshold) {
                    $price += ($points - $threshold) * $extraCost;
                }
            }
        }

        // 2. إضافة الرسوم والضرائب الإجبارية
        $martyrFee = $service->martyr_stamp_fee ?? 5.00; // دمغة شهداء
        $smsFee = $service->sms_fee ?? 10.00;             // رسوم رسائل

        $vatAmount = 0;
        if ($service->has_vat) {
            $vatAmount = $price * 0.14; // ضريبة قيمة مضافة 14%
        }

        // الإجمالي النهائي
        return $price + $vatAmount + $martyrFee + $smsFee;
    }

    /**
     * 7. تتبع الطلب
     */
    public function track(Request $request)
    {
        $id = $request->input('ticket');
        $submission = GisSubmission::with('subService')->find($id);

        if (!$submission) {
            return back()->with('error', 'عفراً، كود المعاملة غير صحيح.');
        }

        return view('gis.tracking.results', compact('submission'));
    }

    /**
     * 8. صفحة النجاح
     */
    public function success($id)
    {
        $submission = GisSubmission::findOrFail($id);
        return view('gis.apply.success', compact('submission'));
    }
}
