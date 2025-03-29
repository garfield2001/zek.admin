<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
    protected $fillable = [
        'menu_type_id',
        'name',
        'description',
        'image',
        'price',
        'status'
    ];

    public function menuType(): BelongsTo
    {
        return $this->belongsTo(MenuType::class);
    }
}
