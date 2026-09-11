<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailyActivity extends Model
{
    use HasFactory;

    protected $table = 'daily_activities';

    protected $fillable = [
        'user_id',
        'activity_date',
        'lessons_completed',
        'tests_completed',
        'xp_earned'
    ];

    protected $casts = [
        'activity_date' => 'date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
