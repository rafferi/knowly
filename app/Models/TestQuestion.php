<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'question',
        'type',
        'options',
        'correct_answer',
        'points',
        'explanation',
        'order_index'
    ];

    protected $casts = [
        'options' => 'array'
    ];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function getOptionsArrayAttribute()
    {
        return $this->options ?? [];
    }
}
