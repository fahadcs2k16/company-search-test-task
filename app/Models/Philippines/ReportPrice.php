<?php

namespace App\Models\Philippines;

use Illuminate\Database\Eloquent\Model;

class ReportPrice extends Model
{
    public function reportType()
    {
        return $this->belongsTo(ReportType::class, 'report_type_id');
    }
}
