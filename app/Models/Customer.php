<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'city',
        'state',
    ];

    // Get the customer's full name
    public function getFullNameAttribute()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    // Get the customer's complete address
    public function getCompleteAddressAttribute()
    {
        return "{$this->address}, {$this->city}, {$this->state}";
    }
}
