<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class Dish extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'menu_type_id',
        'name',
        'description',
        'image_url',
        'price',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
    ];

    /**
     * Get the menu type that owns the dish.
     */
    public function menuType()
    {
        return $this->belongsTo(MenuType::class);
    }

    /**
     * Scope a query to only include available dishes.
     */
    public function scopeAvailable(Builder $query)
    {
        return $query->where('is_available', true);
    }

    /**
     * Scope a query to only include dishes of a specific menu type.
     */
    public function scopeOfMenuType(Builder $query, $menuTypeId)
    {
        return $query->where('menu_type_id', $menuTypeId);
    }

    /**
     * Toggle the availability status of the dish.
     */
    public function toggleAvailability()
    {
        $this->is_available = !$this->is_available;
        $this->save();
        return $this;
    }

    /**
     * Format price for display.
     */
    public function getFormattedPriceAttribute()
    {
        return '₱' . number_format($this->price, 2);
    }
}
