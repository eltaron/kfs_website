<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'landmark_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'number_of_people',
        'visit_date',
        'visit_time',
        'special_requests',
        'status',
        'total_price',
        'notes',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'visit_time' => 'datetime:H:i',
        'total_price' => 'decimal:2',
    ];

    public function landmark(): BelongsTo
    {
        return $this->belongsTo(Landmark::class);
    }

    public function getStatusBadgeColorAttribute(): string
    {
        return match($this->status) {
            'confirmed' => 'success',
            'pending' => 'warning',
            'cancelled' => 'danger',
            'completed' => 'info',
            default => 'secondary',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'confirmed' => 'مؤكد',
            'pending' => 'قيد الانتظار',
            'cancelled' => 'ملغي',
            'completed' => 'مكتمل',
            default => 'غير معروف',
        };
    }
}