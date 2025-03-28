<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'is_available',
        'menu_type_id',
        'image_url'
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price' => 'decimal:2'
    ];

    /**
     * Get the menu type that owns the menu.
     */
    public function menuType(): BelongsTo
    {
        return $this->belongsTo(MenuType::class);
    }

    /**
     * Get the menu availabilities for the menu.
     */
    public function availabilities(): HasMany
    {
        return $this->hasMany(MenuAvailability::class);
    }

    /**
     * Check if the menu is available for catering.
     */
    public function isAvailableForCatering(): bool
    {
        return $this->availabilities()->where('type', 'catering')->exists();
    }

    /**
     * Check if the menu is available for add-ons.
     */
    public function isAvailableForAddon(): bool
    {
        return $this->availabilities()->where('type', 'addon')->exists();
    }

    /**
     * Check if the menu is predefined in any add-on package.
     */
    public function isPredefined(): bool
    {
        return $this->availabilities()->where('is_predefined', true)->exists();
    }

    /**
     * Scope a query to only include catering menus.
     */
    public function scopeCatering($query)
    {
        return $query->whereHas('availabilities', function ($q) {
            $q->where('type', 'catering');
        });
    }

    /**
     * Scope a query to only include addon menus.
     */
    public function scopeAddon($query)
    {
        return $query->whereHas('availabilities', function ($q) {
            $q->where('type', 'addon');
        });
    }

    /**
     * Scope a query to only include predefined menus.
     */
    public function scopePredefined($query)
    {
        return $query->whereHas('availabilities', function ($q) {
            $q->where('is_predefined', true);
        });
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
