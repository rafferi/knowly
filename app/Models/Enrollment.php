<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'status',
        'price',
        'payment_method',
        'payment_id',
        'payment_status',
        'enrolled_at',
        'start_date',
        'end_date',
        'completed_at',
        'notes',
        'metadata'
    ];

    protected $casts = [
        'enrolled_at' => 'datetime',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'completed_at' => 'datetime',
        'metadata' => 'array',
        'price' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function getStatusLabelAttribute()
    {
        $labels = [
            'pending' => '⏳ Ожидает',
            'active' => '✅ Активна',
            'completed' => '🎓 Завершена',
            'cancelled' => '❌ Отменена',
            'expired' => '📅 Истекла'
        ];

        return $labels[$this->status] ?? $this->status;
    }

    public function getPaymentStatusLabelAttribute()
    {
        $labels = [
            'pending' => '⏳ Ожидает оплаты',
            'paid' => '✅ Оплачено',
            'failed' => '❌ Ошибка оплаты',
            'refunded' => '↩️ Возврат'
        ];

        return $labels[$this->payment_status] ?? $this->payment_status;
    }

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function canAccessCourse()
    {
        return in_array($this->status, ['active', 'completed']) &&
            $this->payment_status === 'paid';
    }
}
