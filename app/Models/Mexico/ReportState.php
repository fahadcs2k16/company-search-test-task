<?php

namespace App\Models\Mexico;

use Illuminate\Database\Eloquent\Model;

class ReportState extends Model
{
    protected $connection = 'companies_house_mx';
    protected $table = 'report_state';
    
    protected $fillable = ['state_id', 'report_id', 'amount'];

    public function state()
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function report()
    {
        return $this->belongsTo(Report::class, 'report_id');
    }
}
