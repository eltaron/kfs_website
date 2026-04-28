<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class GisSubmission extends Model
{
    // تعريف المفتاح الأساسي كـ UUID
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'gis_sub_service_id',
        'applicant_info',
        'address_info',
        'request_type',
        'form_data',
        'attachments',
        'payment_status',
        'total_amount',
        'status',
        'inspection_date',
        'is_inspection_confirmed',
        'urban_planning',
        'borders',
        'web_map_url',
        'serial_number'
    ];

    /**
     * تأكد من تحويل كل حقول الـ JSON لمصفوفات لاسترجاع البيانات بسهولة في الفيو
     */
    protected $casts = [
        'applicant_info' => 'array',
        'address_info' => 'array',
        'form_data' => 'array',
        'attachments' => 'array',
        'total_amount' => 'decimal:2',
        'urban_planning' => 'array',
        'borders' => 'array',
        'inspection_date' => 'datetime',
    ];

    /**
     * توليد كود الطلب الفريد تلقائياً (مثل: f85d-4a11-...)
     */
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = (string) Str::uuid();
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subService(): BelongsTo
    {
        return $this->belongsTo(GisSubService::class, 'gis_sub_service_id');
    }
}
