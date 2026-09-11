<?php

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
        'phase',
        'difficulty_level',
        'learning_objectives',
        'is_active',
        'estimated_hours',
        'prerequisites',
        'skills_gained',
        'start_week',
        'end_week'
    ];

    protected $casts = [
        'learning_objectives' => 'array',
        'prerequisites' => 'array',
        'skills_gained' => 'array',
        'is_active' => 'boolean',
        'order_index' => 'integer',
        'estimated_hours' => 'integer'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('order_index');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function tests()
    {
        return $this->hasMany(Test::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index');
    }

    public function getPhaseLabelAttribute()
    {
        $phases = [
            'basic' => 'Начинающий',
            'intermediate' => 'Средний',
            'advanced' => 'Продвинутый'
        ];

        return $phases[$this->phase] ?? $this->phase;
    }
}
