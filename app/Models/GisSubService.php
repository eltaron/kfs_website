<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GisSubService extends Model
{
    protected $fillable = [
        'gis_service_type_id',
        'name',
        'slug',
        'video_url',
        'terms_conditions',
        'requirements',
        'dynamic_fields',
        'base_price',
        'description',
        'pricing_settings',
        'pricing_type',
    ];

    /**
     * تحويل حقل باني النماذج من JSON إلى مصفوفة تلقائياً ليعمل معه الـ Repeater في فيلامنت
     */
    protected $casts = [
        'dynamic_fields' => 'array',
        'pricing_settings' => 'array',
    ];

    public function serviceType(): BelongsTo
    {
        return $this->belongsTo(GisServiceType::class, 'gis_service_type_id');
    }
}
