<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AddonPackage extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'serving_size',
        'price',
        'status'
    ];

    protected $casts = [
        'serving_size' => 'integer',
        'price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function category(): BelongsTo
    {
        return $this->belongsTo(AddonCategory::class, 'category_id');
    }

    public function packageMenus(): HasMany
    {
        return $this->hasMany(AddonPackageMenu::class, 'package_id');
    }

    public function menus()
    {
        return $this->belongsToMany(Menu::class, 'addon_package_menus', 'package_id', 'menu_id')
            ->withPivot(['is_fixed', 'can_change', 'price', 'quantity'])
            ->withTimestamps();
    }
}
