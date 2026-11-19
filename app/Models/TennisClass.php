<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TennisClass extends Model
{
    protected $table = 'classes';

    protected $fillable = [
        'name',
        'description',
        'price',
        'capacity',
        'is_active',
        'type',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'type' => 'string',
    ];
}