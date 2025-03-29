<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class AddonPackage extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'meal_limit',
        'serving_size',
        'price',
        'status'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(AddonCategory::class, 'category_id');
    }

    public function menuTypes(): BelongsToMany
    {
        return $this->belongsToMany(MenuType::class, 'addon_package_menutypes')
            ->withTimestamps();
    }
}
