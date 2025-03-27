<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'meal_limit',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'meal_limit' => 'integer',
    ];

    /**
     * Get the inclusions for the package.
     */
    public function inclusions()
    {
        return $this->hasMany(PackageInclusion::class);
    }
}
