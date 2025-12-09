<?php

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


    public function getOptionsAttribute($value)
    {
        if (is_array($value)) {
            return $value;
        }


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
