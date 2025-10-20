<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'name',
        'email',
        'phone',
        'position',
        'notes',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function inquiries()
{
    return $this->hasMany(Inquiry::class);
}

}
