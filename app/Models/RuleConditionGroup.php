<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RuleConditionGroup extends Model
{
    use HasFactory;

    protected $table = 'rule_condition_groups';

    protected $fillable = [
        'rule_id',
    ];

    /**
     * Get the rule that this condition group belongs to.
     */
    public function rule()
    {
        return $this->belongsTo(Rule::class, 'rule_id');
    }

    /**
     * Get the specific conditions within this group.
     */
    public function conditions()
    {
        return $this->hasMany(RuleCondition::class, 'rule_condition_group_id');
    }
}