<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LearningHistory extends Model
{
    use HasFactory;

    protected $table = 'learning_history';

    protected $fillable = [
        'user_id',
        'action_type',
        'actionable_type',
        'actionable_id',
        'details'
    ];

    protected $casts = [
        'details' => 'array'
    ];

    public $timestamps = false; // Только created_at

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function actionable()
    {
        return $this->morphTo();
    }

    public static function log($userId, $actionType, $actionable = null, $details = [])
    {
        return self::create([
            'user_id' => $userId,
            'action_type' => $actionType,
            'actionable_type' => $actionable ? get_class($actionable) : null,
            'actionable_id' => $actionable ? $actionable->id : null,
            'details' => $details,
            'created_at' => now()
        ]);
    }
}
