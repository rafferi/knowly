<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homework extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'lesson_id',
        'course_id',
        'module_id',
        'content',
        'attachments',
        'answers',
        'status',
        'score',
        'max_score',
        'teacher_feedback',
        'corrections',
        'started_at',
        'submitted_at',
        'reviewed_at',
        'deadline_at',
        'time_spent'
    ];

    protected $casts = [
        'attachments' => 'array',
        'answers' => 'array',
        'corrections' => 'array',
        'started_at' => 'datetime',
        'submitted_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'deadline_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
