<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaktorRisiko;
use App\Models\Rule;
use App\Models\TingkatRisiko;
use Illuminate\Http\Request;

class RuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rules = $this->getRule();

        return view('admin.rule.rule', compact('rules'));
    }

    private function getRule()
    {
        $rules = Rule::with(['tingkatRisiko' => function ($query) {
            $query->select('id', 'name');
        }, 'faktorRisiko' => function ($query) {
            $query->select('id', 'name');
        }])->get(['id', 'tingkat_risiko_id', 'faktor_risiko_id', 'updated_at'])->map(function ($rule) {
            $rule['tingkatRisiko'] = $rule['tingkatRisiko']->toArray();
            $rule['faktorRisiko'] = $rule['faktorRisiko']->toArray();

            return [
                'id' => $rule['id'],
                'updated_at' => $rule['updated_at'],
                'tingkatRisiko' => $rule['tingkatRisiko'],
                'faktorRisiko' => $rule['faktorRisiko'],
                'no_faktor_risiko' => 'FR'.str_pad($rule['faktorRisiko']['id'], 2, '0', STR_PAD_LEFT),
            ];
        })->values()->toArray();

        return $rules;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tingkatRisiko = TingkatRisiko::select('id', 'kode', 'tingkat_risiko')->orderByDesc('updated_at')->get();
        $faktorRisiko = FaktorRisiko::select('id', 'name')->orderByDesc('updated_at')->get();

        $data = [
            'tingkatRisiko' => $tingkatRisiko,
            'faktorRisiko' => $faktorRisiko,
        ];

        return view('admin.rule.tambah', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'tingkatRisiko' => 'required|exists:tingkat_risiko,id',
            'faktorRisiko' => 'required|array',
        ]);

        $faktorRisikoIds = array_unique($request->input('faktorRisiko'));

        foreach ($faktorRisikoIds as $faktorRisikoId) {
            Rule::create([
                'tingkat_risiko_id' => (int) $request->input('tingkatRisiko'),
                'faktor_risiko_id' => (int) $faktorRisikoId,
            ]);
        }

        return redirect()->route('admin.rule')->with('success', 'Rule berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TingkatRisiko  $tingkatRisiko
     * @return \Illuminate\Http\Response
     */
    public function edit(TingkatRisiko $tingkatRisiko)
    {
        $faktorRisiko = FaktorRisiko::select('id', 'name')->orderBy('id')->get();
        $selectedFaktorRisiko = Rule::where('tingkat_risiko_id', $tingkatRisiko->id)->pluck('faktor_risiko_id')->toArray();

        $data = [
            'tingkatRisiko' => $tingkatRisiko,
            'faktorRisiko' => $faktorRisiko,
            'selectedFaktorRisiko' => $selectedFaktorRisiko,
        ];

        return view('admin.rule.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Models\TingkatRisiko  $tingkatRisiko
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TingkatRisiko $tingkatRisiko)
    {
        $request->validate([
            'faktorRisiko' => 'required|array',
        ]);

        Rule::where('tingkat_risiko_id', $tingkatRisiko->id)->delete();

        $faktorRisikoIds = array_unique($request->input('faktorRisiko'));

        foreach ($faktorRisikoIds as $faktor_risiko_id) {
            Rule::create([
                'tingkat_risiko_id' => $tingkatRisiko->id,
                'faktor_risiko_id' => (int) $faktor_risiko_id,
            ]);
        }

        return redirect()->route('admin.rule')->with('success', 'Aturan untuk tingkat risiko '.$tingkatRisiko->tingkat_risiko.' berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TingkatRisiko  $tingkatRisiko
     * @return \Illuminate\Http\Response
     */
    public function destroy(TingkatRisiko $tingkatRisiko)
    {
        Rule::where('tingkat_risiko_id', $tingkatRisiko->id)->delete();

        return redirect()->route('admin.rule')->with('success', 'Semua aturan untuk tingkat risiko '.$tingkatRisiko->tingkat_risiko.' berhasil dihapus');
    }
}
