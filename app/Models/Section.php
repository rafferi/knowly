<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'title',
        'slug',
        'description',
        'order_index',
        'type',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order_index' => 'integer'
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }
}
