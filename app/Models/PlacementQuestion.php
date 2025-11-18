<?php
// [file name]: PlacementQuestion.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlacementQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'level',
        'question',
        'options',
        'correct_index',
        'type',
        'order'
    ];

    protected $casts = [
        'options' => 'array',
    ];

    // Добавим аксессор для гарантированного получения массива
    public function getOptionsAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }

        // Если это JSON строка, декодируем ее
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            return $decoded ?? [];
        }

        return [];
    }

    public function getCorrectAnswerAttribute()
    {
        $options = $this->options;
        return $options[$this->correct_index] ?? null;
    }
}
