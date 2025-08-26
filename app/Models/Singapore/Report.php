<?php

namespace App\Models\Singapore;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $connection = 'companies_house_sg';
    protected $table = 'reports';
    
    protected $fillable = [
        'company_id', 'name', 'type', 'price'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}
