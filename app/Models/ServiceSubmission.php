<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceSubmission extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $casts = [
        'submitted_data' => 'array',
        'paid_at'      => 'datetime',
    ];

    public function transaction()
    {
        return $this->morphOne(Transaction::class, 'transactionable');
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
