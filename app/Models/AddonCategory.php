<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AddonCategory extends Model
{
    protected $fillable = ['name', 'description', 'status'];

    public function addonPackages(): HasMany
    {
        return $this->hasMany(AddonPackage::class, 'category_id');
    }
}
