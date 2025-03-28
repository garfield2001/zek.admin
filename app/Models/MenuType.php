<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MenuType extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the menus of this menu type.
     */
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    /**
     * Get only available menus of this menu type.
     */
    public function availableMenus()
    {
        return $this->hasMany(Menu::class)->where('is_available', true);
    }

    /**
     * Get the count of available menus in this menu type.
     */
    public function getAvailableMenusCountAttribute()
    {
        return $this->menus()->where('is_available', true)->count();
    }
}
