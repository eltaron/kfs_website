<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainingApplication extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'has_taken_previous_courses' => 'boolean',
    ];

    /**
     * Get the program that this application is for.
     */
    public function trainingProgram(): BelongsTo
    {
        return $this->belongsTo(TrainingProgram::class);
    }
}
