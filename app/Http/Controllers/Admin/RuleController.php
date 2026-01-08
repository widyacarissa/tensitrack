<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Rule;
use App\Models\RiskLevel;
use App\Models\RiskFactor;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RuleController extends Controller
{
    public function index(Request $request)
    {
        // Ganti requiredFactor (single) dengan riskFactors (multi)
        $query = Rule::with(['riskLevel', 'riskFactors'])
                    ->orderBy('code', 'ASC');

        if ($request->has('q')) {
            $q = $request->q;
            $query->where('code', 'like', "%{$q}%");
        }

        $rules = $query->paginate(10)->withQueryString();

        if ($this->isMobile()) {
            return view('admin.rules.mobile_index', compact('rules'));
        }

        return view('admin.rules.index', compact('rules'));
    }

    public function print()
    {
        $rules = Rule::with(['riskLevel', 'riskFactors'])
                    ->orderBy('priority', 'ASC')->get();
        $pdf = Pdf::loadView('admin.rules.print', compact('rules'));
        return $pdf->stream('laporan-aturan.pdf');
    }

    public function create()
    {
        $newCode = Rule::generateCode();
        $levels = RiskLevel::all();
        $factors = RiskFactor::all();
        return view('admin.rules.create', compact('newCode', 'levels', 'factors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'risk_level_id' => 'required|exists:risk_levels,id',
            'operator' => 'required|in:AND,OR',
            'min_other_factors' => 'required|numeric|min:0',
            'priority' => 'required|numeric|min:1',
            'risk_factor_ids' => 'nullable|array', // Validasi array checkbox
            'risk_factor_ids.*' => 'exists:risk_factors,id',
        ]);

        $rule = Rule::create([
            'code' => Rule::generateCode(),
            'risk_level_id' => $request->risk_level_id,
            'operator' => $request->operator,
            'min_other_factors' => $request->min_other_factors,
            'max_other_factors' => $request->max_other_factors ?: 99,
            'priority' => $request->priority,
        ]);

        // Simpan checkbox ke tabel pivot
        if ($request->has('risk_factor_ids')) {
            $rule->riskFactors()->sync($request->risk_factor_ids);
        }

        return redirect()->route('admin.rules.index')->with('success', 'Aturan berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $rule = Rule::with('riskFactors')->findOrFail($id);
        $levels = RiskLevel::all();
        $factors = RiskFactor::all();
        return view('admin.rules.edit', compact('rule', 'levels', 'factors'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'risk_level_id' => 'required|exists:risk_levels,id',
            'operator' => 'required|in:AND,OR',
            'min_other_factors' => 'required|numeric|min:0',
            'priority' => 'required|numeric|min:1',
            'risk_factor_ids' => 'nullable|array',
            'risk_factor_ids.*' => 'exists:risk_factors,id',
        ]);

        $rule = Rule::findOrFail($id);
        $rule->update([
            'risk_level_id' => $request->risk_level_id,
            'operator' => $request->operator,
            'min_other_factors' => $request->min_other_factors,
            'max_other_factors' => $request->max_other_factors ?: 99,
            'priority' => $request->priority,
        ]);

        // Sync checkbox (jika kosong, akan menghapus semua relasi)
        $rule->riskFactors()->sync($request->risk_factor_ids ?? []);

        return redirect()->route('admin.rules.index')->with('success', 'Aturan berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $rule = Rule::findOrFail($id);
        $rule->delete(); // Otomatis hapus di pivot table karena on cascade delete

        return redirect()->route('admin.rules.index')->with('success', 'Aturan berhasil dihapus.');
    }
}
