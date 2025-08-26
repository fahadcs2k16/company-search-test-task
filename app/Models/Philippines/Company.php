<?php

namespace App\Models\Philippines;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $connection = 'companies_house_ph';
    protected $table = 'companies';
    
    protected $fillable = [
        'name', 'slug', 'sec_code', 'address'
    ];

    public function reports()
    {
        return $this->hasMany(Report::class, 'company_id');
    }
}
