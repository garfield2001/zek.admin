<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AddonPackageMenu extends Model
{
    protected $fillable = [
        'package_id',
        'menu_id',
        'is_fixed',
        'can_change',
        'price',
        'quantity'
    ];

    protected $casts = [
        'is_fixed' => 'boolean',
        'can_change' => 'boolean',
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function package(): BelongsTo
    {
        return $this->belongsTo(AddonPackage::class, 'package_id');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class, 'menu_id');
    }
}
