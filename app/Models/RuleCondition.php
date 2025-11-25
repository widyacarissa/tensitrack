<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuleCondition extends Model
{
    use HasFactory;

    protected $table = 'rule_conditions';

    protected $fillable = [
        'rule_condition_group_id',
        'type',
        'faktor_risiko_id',
        'operator',
        'value',
    ];

    /**
     * Get the condition group that this condition belongs to.
     */
    public function conditionGroup()
    {
        return $this->belongsTo(RuleConditionGroup::class, 'rule_condition_group_id');
    }

    /**
     * Get the risk factor associated with this condition (if any).
     */
    public function faktorRisiko()
    {
        return $this->belongsTo(FaktorRisiko::class, 'faktor_risiko_id');
    }
}