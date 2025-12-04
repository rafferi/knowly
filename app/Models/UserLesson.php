<?php
// app/Models/UserLesson.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'completed',
        'score',
        'time_spent',
        'completed_at',
        'module_id',
'week_number',
'lesson_stats',
'completion_percentage',
'started_at',
'last_accessed_at'
    ];

    protected $casts = [
        'completed' => 'boolean',
        'completed_at' => 'datetime',
        'lesson_stats' => 'array'
    ];
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
