<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    use HasFactory;

    protected $table = 'user_progress';

    protected $fillable = [
        'user_id',
        'trackable_type',
        'trackable_id',
        'status',
        'progress_percentage',
        'score',
        'time_spent',
        'attempts',
        'started_at',
        'completed_at',
        'last_accessed_at',
        'metadata'
    ];

    protected $casts = [
        'progress_percentage' => 'decimal:2',
        'score' => 'decimal:2',
        'metadata' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'last_accessed_at' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function trackable()
    {
        return $this->morphTo();
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeForTrackable($query, $type, $id)
    {
        return $query->where('trackable_type', $type)
            ->where('trackable_id', $id);
    }
}
