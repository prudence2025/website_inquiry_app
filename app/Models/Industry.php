<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
    ];

    public function companies()
    {
        return $this->belongsToMany(Company::class);
    }

    public function inquiries()
{
    return $this->hasMany(Inquiry::class);
}

}
