<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonContent extends Model
{
    use HasFactory;

    protected $table = 'lesson_contents';

    protected $fillable = [
        'lesson_id',
        'title',
        'content_type',
        'content',
        'order_index',
        'duration',
        'settings'
    ];

    protected $casts = [
        'settings' => 'array',
        'order_index' => 'integer',
        'duration' => 'integer'
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index');
    }
}
