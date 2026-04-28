<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExamSubmission extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'answers' => 'array',
        'passed' => 'boolean',
        'submitted_at' => 'datetime',
    ];

    /**
     * Get the exam that the submission belongs to.
     */
    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the user who made the submission.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
