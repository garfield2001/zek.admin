<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuAvailability extends Model
{
    protected $table = 'menu_availability';

    protected $fillable = [
        'menu_id',
        'type',
        'is_predefined'
    ];

    protected $casts = [
        'is_predefined' => 'boolean'
    ];

    /**
     * Get the menu that owns the availability.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }

    /**
     * Scope a query to only include catering menus.
     */
    public function scopeCatering($query)
    {
        return $query->where('type', 'catering');
    }

    /**
     * Scope a query to only include addon menus.
     */
    public function scopeAddon($query)
    {
        return $query->where('type', 'addon');
    }

    /**
     * Scope a query to only include predefined menus.
     */
    public function scopePredefined($query)
    {
        return $query->where('is_predefined', true);
    }
}
