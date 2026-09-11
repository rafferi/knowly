<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Level extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'title',
        'description',
        'order_index',
        'color',
        'icon'
    ];

    public function courses()
    {
        return $this->hasMany(Course::class, 'level_id');
    }

    public function targetCourses()
    {
        return $this->hasMany(Course::class, 'target_level_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('order_index');
    }
}
