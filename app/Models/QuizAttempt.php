<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class QuizAttempt extends Model
{
    protected $table = 'quiz_attempts';
    
    protected $fillable = [
        'user_id',
        'file_name',
        'quiz_data',
        'correct_answers',
        'total_questions',
        'percentage'
    ];

    protected $casts = [
        'quiz_data' => 'json',
        'percentage' => 'float'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
