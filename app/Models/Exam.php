<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exam extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * Get the trainingProgram that owns the Exam.
     */
    public function trainingProgram(): BelongsTo
    {
        return $this->belongsTo(TrainingProgram::class);
    }

    /**
     * Get all of the questions for the Exam.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(ExamQuestion::class);
    }

    /**
     * Get all of the submissions for the Exam.
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(ExamSubmission::class);
    }
}
