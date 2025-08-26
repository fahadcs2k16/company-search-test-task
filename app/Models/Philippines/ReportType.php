<?php

namespace App\Models\Philippines;

use Illuminate\Database\Eloquent\Model;

class ReportType extends Model
{
    protected $connection = 'companies_house_ph';
    protected $table = 'report_types';
    
    protected $fillable = ['name', 'price'];

    public function reports()
    {
        return $this->hasMany(Report::class, 'report_type_id');
    }
}
