<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PackageInclusion extends Model
{
    use HasFactory;

    protected $fillable = [
        'package_id',
        'name',
        'description',
    ];

    /**
     * Get the package that owns the inclusion.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }
}
