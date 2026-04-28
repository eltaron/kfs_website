<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RemovalOrder extends Model
{
    protected $fillable = [
        'violation_type',
        'license_number',
        'license_date',
        'licensed_works',
        'center',
        'local_unit',
        'street',
        'violation_area',
        'district',
        'owner_name',
        'owner_national_id',
        'owner_center',
        'owner_unit',
        'owner_street',
        'owner_district',
        'owner_governorate',
        'engineer_name',
        'engineer_national_id',
        'contractor_name',
        'contractor_national_id',
        'violation_plot',
        'violation_dimensions',
        'violation_cost',
        'violation_works',
        'stop_order_number',
        'stop_order_date',
        'violation_report_number',
        'report_date',
        'announcement_date',
        'status',
        'sketch_file',
        'photo_file',
        'created_by'
    ];

    // تحويل التواريخ لكائنات Carbon تلقائياً لسهولة التعامل معها في Filament
    protected $casts = [
        'license_date' => 'date',
        'stop_order_date' => 'date',
        'report_date' => 'date',
        'announcement_date' => 'date',
        'violation_cost' => 'decimal:2',
        'borders' => 'array',
    ];

    /**
     * الموظف الذي أنشأ القرار
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
