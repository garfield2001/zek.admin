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
     * Get the dishes of this menu type.
     */
    public function dishes()
    {
        return $this->hasMany(Dish::class);
    }

    /**
     * Get only available dishes of this menu type.
     */
    public function availableDishes()
    {
        return $this->hasMany(Dish::class)->where('is_available', true);
    }

    /**
     * Get the count of available dishes in this menu type.
     */
    public function getAvailableDishesCountAttribute()
    {
        return $this->dishes()->where('is_available', true)->count();
    }
}
