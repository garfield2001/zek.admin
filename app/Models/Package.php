<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'price',
        'meal_limit',
        'minimum_guests',
        'maximum_guests',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'meal_limit' => 'integer',
        'minimum_guests' => 'integer',
        'maximum_guests' => 'integer',
        'status' => 'string',
    ];
}
