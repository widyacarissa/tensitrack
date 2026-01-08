<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiskFactor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;

class RiskFactorController extends Controller
{
    public function index(Request $request)
    {
        $query = RiskFactor::query();

        if ($request->has('q')) {
            $q = $request->q;
            $query->where('name', 'like', "%{$q}%")
                  ->orWhere('code', 'like', "%{$q}%");
        }

        $riskFactors = $query->orderBy('code', 'asc')->paginate(10)->withQueryString();

        if ($this->isMobile()) {
            return view('admin.risk-factors.mobile_index', compact('riskFactors'));
        }

        return view('admin.risk-factors.index', compact('riskFactors'));
    }

    public function print()
    {
        $riskFactors = RiskFactor::orderBy('code', 'asc')->get();
        $pdf = Pdf::loadView('admin.risk-factors.print', compact('riskFactors'));
        return $pdf->stream('laporan-faktor-risiko.pdf');
    }

    public function create()
    {
        $newCode = RiskFactor::generateCode();
        return view('admin.risk-factors.create', compact('newCode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'question_text' => 'required|string',
            'medical_explanation' => 'nullable|string',
            'recommendation' => 'nullable|string',
        ]);

        RiskFactor::create([
            'code' => RiskFactor::generateCode(),
            'name' => $request->name,
            'question_text' => $request->question_text,
            'medical_explanation' => $request->medical_explanation,
            'recommendation' => $request->recommendation,
        ]);

        return redirect()->route('admin.risk-factors.index')->with('success', 'Faktor risiko berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $riskFactor = RiskFactor::findOrFail($id);
        return view('admin.risk-factors.edit', compact('riskFactor'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string',
            'question_text' => 'required|string',
            'medical_explanation' => 'nullable|string',
            'recommendation' => 'nullable|string',
        ]);

        $riskFactor = RiskFactor::findOrFail($id);
        $riskFactor->update($request->all());

        return redirect()->route('admin.risk-factors.index')->with('success', 'Faktor risiko berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        DB::transaction(function () use ($id) {
            $riskFactor = RiskFactor::findOrFail($id);
            $riskFactor->delete();

            // Re-sequence codes (E01, E02, ...)
            $factors = RiskFactor::orderBy('code', 'asc')->get();
            foreach ($factors as $index => $factor) {
                $newCode = 'E' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);
                if ($factor->code !== $newCode) {
                    $factor->timestamps = false;
                    $factor->update(['code' => $newCode]);
                    $factor->timestamps = true;
                }
            }
        });

        return redirect()->route('admin.risk-factors.index')->with('success', 'Faktor risiko berhasil dihapus.');
    }
}