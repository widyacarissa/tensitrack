<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScreeningDetail extends Model
{
    protected $fillable = ['screening_id', 'risk_factor_id'];

    public function screening()
    {
        return $this->belongsTo(Screening::class);
    }

    public function riskFactor()
    {
        return $this->belongsTo(RiskFactor::class);
    }
}