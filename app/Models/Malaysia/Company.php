<?php

namespace App\Models\Malaysia;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $connection = 'companies_house_my';
    protected $table = 'companies';
    
    protected $fillable = [
        'name', 'slug', 'registration_number', 'company_type_id', 'address'
    ];

    public function companyType()
    {
        return $this->belongsTo(CompanyType::class, 'company_type_id');
    }

    public function availableReports()
    {
        return $this->hasMany(Report::class, 'company_type_id', 'company_type_id')
                    ->where('status', 1);
    }
}
