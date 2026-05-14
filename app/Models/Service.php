<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Service extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [];

    protected $casts = [
        'is_highlighted' => 'boolean',
        'form_fields' => 'array',
        'category_pricing' => 'array', // ضروري جداً للتعامل مع الفئات كـ JSON
        'has_vat' => 'boolean',
    ];

    public function parent()
    {
        return $this->belongsTo(Service::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Service::class, 'parent_id');
    }

    /**
     * دالة حساب السعر النهائي ديناميكياً
     * $area: مساحة المتر
     * $categoryName: اسم الفئة المختارة (مثل: حي شرق)
     */
    public function calculateTotal($area = 1, $categoryName = null)
    {
        $base = $this->base_price;

        // 1. منطق التسعير (فئات أو ثابت)
        if ($this->pricing_type === 'category' && $this->category_pricing) {
            foreach ($this->category_pricing as $cat) {
                if ($cat['name'] === $categoryName) {
                    $base = $base * $cat['price_multiplier'];
                    break;
                }
            }
        }

        $total = ($base * $area);

        // 2. إضافة التأمين
        $total += $this->insurance_fee;

        // 3. إضافة الضريبة (14%)
        if ($this->has_vat) {
            $total += ($total * 0.14);
        }

        // 4. الرسوم الثابتة
        $total += $this->martyr_stamp_fee;
        $total += $this->sms_fee;

        return $total;
    }

    // للإبقاء على التوافق مع الكود القديم (سعر افتراضي)
    protected function totalPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->calculateTotal(1)
        );
    }
}