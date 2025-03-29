<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\MenuType;

class Package extends Model
{
    protected $fillable = [
        'name',
        'price',
        'meal_limit',
        'minimum_guests',
        'maximum_guests',
        'status',
        'icon',
        'description',
        'features'
    ];

    protected $casts = [
        'features' => 'array'
    ];

    public function menuTypes(): BelongsToMany
    {
        return $this->belongsToMany(MenuType::class, 'package_menutypes')
            ->withTimestamps();
    }
}
