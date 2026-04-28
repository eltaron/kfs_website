<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TrainingProgram extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the trainingCenter that owns the TrainingProgram.
     */
    public function trainingCenter(): BelongsTo
    {
        return $this->belongsTo(TrainingCenter::class);
    }

    /**
     * Get all of the enrollments for the TrainingProgram.
     */
    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    /**
     * Get all of the programModules for the TrainingProgram.
     */
    public function programModules(): HasMany
    {
        return $this->hasMany(ProgramModule::class);
    }

    /**
     * Get all of the exams for the TrainingProgram.
     */
    public function exams(): HasMany
    {
        return $this->hasMany(Exam::class);
    }
    public function finalExam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }
}
