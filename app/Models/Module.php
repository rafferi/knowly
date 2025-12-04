<?php
// app/Models/Module.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'description',
        'order_index',
        'duration_weeks',
        'phase',
        'start_week',
        'end_week',
        'learning_objectives',
        'is_active'
    ];

    protected $casts = [
        'learning_objectives' => 'array',
        'is_active' => 'boolean',
        'duration_weeks' => 'integer',
        'start_week' => 'integer',
        'end_week' => 'integer'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
