<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RiskLevel;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class RiskLevelController extends Controller
{
    public function index(Request $request)
    {
        $query = RiskLevel::query();

        if ($request->has('q')) {
            $q = $request->q;
            $query->where('name', 'like', "%{$q}%")
                  ->orWhere('code', 'like', "%{$q}%")
                  ->orWhere('description', 'like', "%{$q}%");
        }

        $riskLevels = $query->paginate(10)->withQueryString();

        if ($this->isMobile()) {
            return view('admin.risk-levels.mobile_index', compact('riskLevels'));
        }

        return view('admin.risk-levels.index', compact('riskLevels'));
    }

    public function print()
    {
        $riskLevels = RiskLevel::all();
        $pdf = Pdf::loadView('admin.risk-levels.print', compact('riskLevels'));
        return $pdf->stream('laporan-tingkat-risiko.pdf');
    }

    public function create()
    {
        $newCode = RiskLevel::generateCode();
        return view('admin.risk-levels.create', compact('newCode'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'suggestion' => 'required|string',
        ]);

        RiskLevel::create([
            'code' => RiskLevel::generateCode(), // Generate lagi untuk memastikan unik
            'name' => $request->name,
            'description' => $request->description,
            'suggestion' => $request->suggestion,
        ]);

        return redirect()->route('admin.risk-levels.index')->with('success', 'Tingkat risiko berhasil ditambahkan.');
    }

    public function edit(string $id)
    {
        $riskLevel = RiskLevel::findOrFail($id);
        return view('admin.risk-levels.edit', compact('riskLevel'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'suggestion' => 'required|string',
        ]);

        $riskLevel = RiskLevel::findOrFail($id);
        $riskLevel->update($request->all());

        return redirect()->route('admin.risk-levels.index')->with('success', 'Tingkat risiko berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $riskLevel = RiskLevel::findOrFail($id);
        $riskLevel->delete();

        return redirect()->route('admin.risk-levels.index')->with('success', 'Tingkat risiko berhasil dihapus.');
    }
}
