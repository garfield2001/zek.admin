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
        'min_guests',
        'menu_limit',
        'predefined_drinks',
        'icon',
        'description',
        'features'
    ];

    protected $casts = [
        'features' => 'array',
        'predefined_drinks' => 'boolean',
        'price' => 'decimal:2'
    ];

    // Helper method to get formatted features
    public function getFormattedFeatures()
    {
        return collect($this->features)->map(function ($feature) {
            return [
                'text' => $feature['feature'],
                'icon' => $feature['icon'] ?? null
            ];
        });
    }
}
