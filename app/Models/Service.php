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
    ];


    public function parent()
    {
        return $this->belongsTo(Service::class, 'parent_id');
    }

    // A service can have many children
    public function children()
    {
        return $this->hasMany(Service::class, 'parent_id');
    }
    protected function totalPrice(): Attribute
    {
        return Attribute::make(
            get: function () {
                $total = $this->base_price;

                // Add VAT (14%) if applicable
                if ($this->has_vat) {
                    $total += $this->base_price * 0.14;
                }

                // Add fixed fees
                $total += $this->martyr_stamp_fee;
                $total += $this->sms_fee;

                return $total;
            }
        );
    }
}
