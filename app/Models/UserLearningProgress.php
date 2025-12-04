<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLearningProgress extends Model
{
    use HasFactory;

    protected $table = 'user_learning_progress';

    protected $fillable = [
        'user_id',
        'course_id',
        'current_week',
        'current_module_id',
        'total_weeks',
        'start_date',
        'estimated_completion',
        'actual_completion',
        'completed_weeks',
        'completed_module_ids',
        'overall_progress',
        'weekly_progress',
        'total_study_time',
        'lessons_completed',
        'homeworks_submitted',
        'last_study_date',
        'weekly_goal_hours',
        'notifications_enabled',
        'learning_pace'
    ];

    protected $casts = [
        'completed_weeks' => 'array',
        'completed_module_ids' => 'array',
        'weekly_progress' => 'array',
        'start_date' => 'date',
        'estimated_completion' => 'date',
        'actual_completion' => 'date',
        'last_study_date' => 'datetime',
        'notifications_enabled' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function currentModule()
    {
        return $this->belongsTo(Module::class, 'current_module_id');
    }
}
