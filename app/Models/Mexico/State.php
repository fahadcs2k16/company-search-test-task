<?php

namespace App\Models\Mexico;

use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    protected $connection = 'companies_house_mx';
    protected $table = 'states';
    
    protected $fillable = ['name'];

    public function companies()
    {
        return $this->hasMany(Company::class, 'state_id');
    }

    public function reportStates()
    {
        return $this->hasMany(ReportState::class, 'state_id');
    }
}
