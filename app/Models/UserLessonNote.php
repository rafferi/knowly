<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserLessonNote extends Model
{
    use HasFactory;

    protected $table = 'user_lesson_notes';

    protected $fillable = [
        'user_id',
        'lesson_id',
        'content',
        'is_private'
    ];

    protected $casts = [
        'is_private' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
