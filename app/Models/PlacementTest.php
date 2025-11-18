<?php
// [file name]: PlacementTest.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlacementTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'score',
        'level',
        'answers',
        'total_questions',
        'correct_answers',
        'completed_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getPercentageAttribute()
    {
        return $this->total_questions > 0 ? round(($this->correct_answers / $this->total_questions) * 100) : 0;
    }
}
