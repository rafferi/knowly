<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'test_id',
        'course_id',
        'score',
        'total_questions',
        'correct_answers',
        'attempt_number',
        'passed',
        'time_spent',
        'started_at',
        'completed_at',
        'answers',
        'correct_answers_list',
        'question_details'
    ];

    protected $casts = [
        'answers' => 'array',
        'correct_answers_list' => 'array',
        'question_details' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'passed' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function getPercentageAttribute()
    {
        return $this->total_questions > 0 ?
            round(($this->correct_answers / $this->total_questions) * 100) : 0;
    }

    public function getTimeFormattedAttribute()
    {
        $minutes = floor($this->time_spent / 60);
        $seconds = $this->time_spent % 60;
        return sprintf('%02d:%02d', $minutes, $seconds);
    }
}
