<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'website',
        'contact_person',
        'description',
    ];

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function industries()
    {
        return $this->belongsToMany(Industry::class);
    }
}
