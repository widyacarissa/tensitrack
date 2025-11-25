<?php

namespace App\Services;

use App\Models\FaktorRisiko;
use App\Models\Rule;
use Illuminate\Support\Collection;

class DiagnosisService
{
    /**
     * Calculate the risk level based on a set of patient factors.
     *
     * @param array $submittedFaktorIds An array of IDs from the 'faktor_risiko' table.
     * @return \App\Models\TingkatRisiko|null
     */
    public function calculateRisk(array $submittedFaktorIds)
    {
        // Eager load relationships for efficiency
        $rules = Rule::with(['conditionGroups.conditions.faktorRisiko', 'tingkatRisiko'])
            ->orderBy('priority', 'desc')
            ->get();

        // Get the full objects for the submitted factors
        $submittedFactors = FaktorRisiko::whereIn('id', $submittedFaktorIds)->get();

        foreach ($rules as $rule) {
            // A rule matches if ANY of its condition groups are met (OR logic)
            $isRuleMatch = false;
            foreach ($rule->conditionGroups as $group) {
                if ($this->isGroupMatch($group, $submittedFactors)) {
                    $isRuleMatch = true;
                    break; // Met the OR condition, no need to check other groups for this rule
                }
            }

            if ($isRuleMatch) {
                return $rule->tingkatRisiko; // Return the associated risk level and stop
            }
        }

        return null; // Or return a default low-risk level
    }

    /**
     * Check if a condition group is a match.
     *
     * @param \App\Models\RuleConditionGroup $group
     * @param \Illuminate\Support\Collection $submittedFactors
     * @return bool
     */
    private function isGroupMatch($group, Collection $submittedFactors): bool
    {
        // A group matches if ALL of its conditions are met (AND logic)
        foreach ($group->conditions as $condition) {
            if (!$this->isConditionMatch($condition, $submittedFactors)) {
                return false; // One condition failed, the whole group fails
            }
        }
        return true; // All conditions in the group passed
    }

    /**
     * Check if a single condition is a match.
     *
     * @param \App\Models\RuleCondition $condition
     * @param \Illuminate\Support\Collection $submittedFactors
     * @return bool
     */
    private function isConditionMatch($condition, Collection $submittedFactors): bool
    {
        switch ($condition->type) {
            case 'HAS_FAKTOR':
                $hasFactor = $submittedFactors->pluck('id')->contains($condition->faktor_risiko_id);
                $expected = (bool) $condition->value; // 1 becomes true, 0 becomes false
                return $this->evaluate($hasFactor, $condition->operator, $expected);

            case 'FAKTOR_LAIN_COUNT': // Count of factors OTHER THAN E01
                $e01 = FaktorRisiko::where('kode', 'E01')->first();
                $count = $submittedFactors->where('id', '!=', $e01 ? $e01->id : -1)->count();
                return $this->evaluate($count, $condition->operator, $condition->value);

            case 'FAKTOR_TOTAL_COUNT': // Count of ALL submitted factors
                $count = $submittedFactors->count();
                return $this->evaluate($count, $condition->operator, $condition->value);

            case 'GAYA_HIDUP_COUNT':
                $count = $submittedFactors->where('tipe', 'GAYA_HIDUP')->count();
                return $this->evaluate($count, $condition->operator, $condition->value);
                
            default:
                return false;
        }
    }

    /**
     * Evaluate a comparison based on an operator.
     *
     * @param int|bool $actual
     * @param string $operator
     * @param int|bool $expected
     * @return bool
     */
    private function evaluate($actual, string $operator, $expected): bool
    {
        // Cast boolean to int for comparison
        if (is_bool($actual)) $actual = (int) $actual;
        if (is_bool($expected)) $expected = (int) $expected;

        switch ($operator) {
            case '>=':
                return $actual >= $expected;
            case '<=':
                return $actual <= $expected;
            case '==':
                return $actual == $expected;
            case '!=':
                return $actual != $expected;
            case '>':
                return $actual > $expected;
            case '<':
                return $actual < $expected;
            default:
                return false;
        }
    }
}
