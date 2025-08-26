<?php

namespace App\Models\Mexico;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $connection = 'companies_house_mx';
    protected $table = 'companies';
    
    protected $fillable = [
        'name', 'slug', 'registration_number', 'state_id', 'address'
    ];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function availableReports()
    {
        return $this->hasMany(ReportState::class, 'state_id', 'state_id');
    }
}
