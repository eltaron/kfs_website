<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GisMarkaz extends Model
{
    protected $fillable = ['name', 'gis_code'];

    /**
     * المركز الواحد يتبع له عدة وحدات محلية / شياخات
     */
    public function shiakhas(): HasMany
    {
        return $this->hasMany(GisShiakha::class, 'gis_markaz_id');
    }
}
