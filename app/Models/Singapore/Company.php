<?php

namespace App\Models\Singapore;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $connection = 'companies_house_sg';
    protected $table = 'companies';
    
    protected $fillable = [
        'name', 'slug', 'registration_number', 'address'
    ];

    public function reports()
    {
        return $this->hasMany(Report::class);
    }
}
