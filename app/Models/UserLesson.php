<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'module_id',
        'section_id',
        'completed',
        'score',
        'completion_percentage',
        'time_spent',
        'lesson_stats',
        'completed_at',
        'started_at',
        'last_accessed_at'
    ];

    protected $casts = [
        'completed' => 'boolean',
        'completed_at' => 'datetime',
        'started_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'lesson_stats' => 'array',
        'completion_percentage' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
