<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    protected $fillable = [
        'code',
        'risk_level_id',
        'operator',
        'min_other_factors',
        'max_other_factors',
        'priority',
    ];

    public function riskLevel()
    {
        return $this->belongsTo(RiskLevel::class);
    }

    public function riskFactors()
    {
        return $this->belongsToMany(RiskFactor::class, 'rule_risk_factors', 'rule_id', 'risk_factor_id');
    }

    // Fungsi generate kode otomatis (R1, R2...)
    public static function generateCode()
    {
        $last = self::orderBy('id', 'DESC')->first();

        if (!$last) {
            return 'R1';
        }

        $lastCode = $last->code;
        $number   = (int) substr($lastCode, 1);
        $nextNumber = $number + 1;

        return 'R' . $nextNumber;
    }
}