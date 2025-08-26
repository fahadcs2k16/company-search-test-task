<?php

namespace App\Models\Mexico;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $connection = 'companies_house_mx';
    protected $table = 'reports';
    
    protected $fillable = ['name', 'type'];

    public function reportStates()
    {
        return $this->hasMany(ReportState::class, 'report_id');
    }
}
