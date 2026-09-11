<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    use HasFactory;

    protected $table = 'certificates';

    protected $fillable = [
        'certificate_number',
        'user_id',
        'course_id',
        'user_course_id',
        'course_title',
        'level',
        'duration',
        'lessons_count',
        'lessons_completed',
        'average_score',
        'issue_date',
        'expiry_date',
        'verification_code',
        'metadata'
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'metadata' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function userCourse()
    {
        return $this->belongsTo(UserCourse::class);
    }
}
