<?php

namespace App\Models\Philippines;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $connection = 'companies_house_ph';
    protected $table = 'reports';

    protected $fillable = [
        'company_id', 'report_price_id', 'period_date'
    ];

    protected $casts = [
        'period_date' => 'date'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function reportPrice()
    {
        return $this->belongsTo(ReportPrice::class, 'report_price_id');
    }

    // Shortcut: through reportPrice → reportType
    public function reportType()
    {
        return $this->hasOneThrough(
            ReportType::class,
            ReportPrice::class,
            'id',              // report_prices.id
            'id',              // report_types.id
            'report_price_id', // reports.report_price_id
            'report_type_id'   // report_prices.report_type_id
        );
    }
}
