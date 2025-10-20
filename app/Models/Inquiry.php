<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'customer_id',
        'industry_id',
        'inquiry_date',
        'receiver_name',
        'requirement_type',
        'customer_name',
        'company_name',
        'more_info',
        'amount',
        'process_level',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function industry()
    {
        return $this->belongsTo(Industry::class);
    }
    public function requirementType()
    {
        return $this->belongsTo(RequirementType::class);
    }

    public function industries()
    {
        return $this->belongsToMany(Industry::class, 'company_industry', 'company_id', 'industry_id');
    }

}
