<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaktorRisiko;
use App\Models\Rule;
use App\Models\TingkatRisiko;
use App\Http\Requests\Admin\StoreRuleRequest;
use App\Models\RuleConditionGroup;
use App\Models\RuleCondition;
use Illuminate\Support\Facades\DB;

class RuleController extends Controller
{
    /**
     * Display a listing of the rules.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rules = Rule::with('tingkatRisiko')->orderBy('priority', 'desc')->get();
        return view('admin.rules.index', compact('rules'));
    }

    /**
     * Show the form for creating a new rule.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tingkatRisikos = TingkatRisiko::all();
        $faktorRisikos = FaktorRisiko::all();
        return view('admin.rules.create', compact('tingkatRisikos', 'faktorRisikos'));
    }

    /**
     * Store a newly created rule in storage.
     *
     * @param  \App\Http\Requests\Admin\StoreRuleRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRuleRequest $request)
    {
        DB::transaction(function () use ($request) {
            $rule = Rule::create([
                'name' => $request->name,
                'description' => $request->description,
                'tingkat_risiko_id' => $request->tingkat_risiko_id,
                'priority' => $request->priority,
            ]);

            foreach ($request->condition_groups as $groupData) {
                $conditionGroup = $rule->conditionGroups()->create();
                foreach ($groupData['conditions'] as $conditionData) {
                    $conditionGroup->conditions()->create([
                        'type' => $conditionData['type'],
                        'faktor_risiko_id' => $conditionData['faktor_risiko_id'] ?? null,
                        'operator' => $conditionData['operator'],
                        'value' => $conditionData['value'],
                    ]);
                }
            }
        });

        return redirect()->route('admin.rules.index')->with('success', 'Aturan berhasil ditambahkan!');
    }

    /**
     * Display the specified rule.
     *
     * @param  \App\Models\Rule  $rule
     * @return \Illuminate\Http\Response
     */
    public function show(Rule $rule)
    {
        $rule->load('tingkatRisiko', 'conditionGroups.conditions.faktorRisiko');
        return view('admin.rules.show', compact('rule'));
    }

    /**
     * Show the form for editing the specified rule.
     *
     * @param  \App\Models\Rule  $rule
     * @return \Illuminate\Http\Response
     */
    public function edit(Rule $rule)
    {
        $tingkatRisikos = TingkatRisiko::all();
        $faktorRisikos = FaktorRisiko::all();
        $rule->load('conditionGroups.conditions'); // Eager load for the form
        return view('admin.rules.edit', compact('rule', 'tingkatRisikos', 'faktorRisikos'));
    }

    /**
     * Update the specified rule in storage.
     *
     * @param  \App\Http\Requests\Admin\StoreRuleRequest  $request
     * @param  \App\Models\Rule  $rule
     * @return \Illuminate\Http\Response
     */
    public function update(StoreRuleRequest $request, Rule $rule)
    {
        DB::transaction(function () use ($request, $rule) {
            $rule->update([
                'name' => $request->name,
                'description' => $request->description,
                'tingkat_risiko_id' => $request->tingkat_risiko_id,
                'priority' => $request->priority,
            ]);

            // Delete existing conditions and groups, then recreate
            $rule->conditionGroups()->each(function ($group) {
                $group->conditions()->delete();
            });
            $rule->conditionGroups()->delete();

            foreach ($request->condition_groups as $groupData) {
                $conditionGroup = $rule->conditionGroups()->create();
                foreach ($groupData['conditions'] as $conditionData) {
                    $conditionGroup->conditions()->create([
                        'type' => $conditionData['type'],
                        'faktor_risiko_id' => $conditionData['faktor_risiko_id'] ?? null,
                        'operator' => $conditionData['operator'],
                        'value' => $conditionData['value'],
                    ]);
                }
            }
        });

        return redirect()->route('admin.rules.index')->with('success', 'Aturan berhasil diperbarui!');
    }

    /**
     * Remove the specified rule from storage.
     *
     * @param  \App\Models\Rule  $rule
     * @return \Illuminate\Http\Response
     */
    public function destroy(Rule $rule)
    {
        DB::transaction(function () use ($rule) {
            $rule->conditionGroups()->each(function ($group) {
                $group->conditions()->delete();
            });
            $rule->conditionGroups()->delete();
            $rule->delete();
        });

        return redirect()->route('admin.rules.index')->with('success', 'Aturan berhasil dihapus!');
    }
}
