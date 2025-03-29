<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\MenuTypeCategory;

class MenuType extends Model
{
    protected $fillable = ['name', 'description'];

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'package_menutypes')
            ->withTimestamps();
    }

    public function addonPackages(): BelongsToMany
    {
        return $this->belongsToMany(AddonPackage::class, 'addon_package_menutypes')
            ->withTimestamps();
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(MenuTypeCategory::class, 'menutype_categories', 'menu_type_id', 'category')
            ->withTimestamps();
    }
}
