<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Screening;
use App\Models\RiskLevel;
use App\Models\RiskFactor;
use App\Models\Rule;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // 1. Hitung Total
        $totalScreenings = Screening::count();
        $totalUsers = User::where('role', 'client')->count(); // Hanya hitung pasien
        $totalRiskFactors = RiskFactor::count();
        $totalRiskLevels = RiskLevel::count();
        $totalRules = Rule::count();

        // 2. Hitung Distribusi Risiko (4 Kategori)
        $riskCounts = [
            'Tidak Berisiko' => 0,
            'Rendah' => 0,
            'Sedang' => 0,
            'Tinggi' => 0,
        ];

        // Ambil semua result_level untuk dihitung
        $results = Screening::pluck('result_level');

        foreach ($results as $level) {
            $l = strtolower($level);
            if (stripos($l, 'berat') !== false || stripos($l, 'tinggi') !== false) {
                $riskCounts['Tinggi']++;
            } elseif (stripos($l, 'sedang') !== false) {
                $riskCounts['Sedang']++;
            } elseif (stripos($l, 'rendah') !== false || stripos($l, 'ringan') !== false) {
                $riskCounts['Rendah']++;
            } else {
                // Default / Aman / Tidak Berisiko
                $riskCounts['Tidak Berisiko']++;
            }
        }

        // 3. Hitung Persentase
        $riskPercentages = [
            'Tidak Berisiko' => $totalScreenings > 0 ? round(($riskCounts['Tidak Berisiko'] / $totalScreenings) * 100) : 0,
            'Rendah' => $totalScreenings > 0 ? round(($riskCounts['Rendah'] / $totalScreenings) * 100) : 0,
            'Sedang' => $totalScreenings > 0 ? round(($riskCounts['Sedang'] / $totalScreenings) * 100) : 0,
            'Tinggi' => $totalScreenings > 0 ? round(($riskCounts['Tinggi'] / $totalScreenings) * 100) : 0,
        ];

        // Warna untuk UI
        $riskLevelColors = [
            'Tidak Berisiko' => 'bg-green-500',
            'Rendah' => 'bg-blue-500',
            'Sedang' => 'bg-orange-500',
            'Tinggi' => 'bg-red-500',
        ];

        // 4. Aktivitas Terbaru (5 Terakhir)
        $latestScreenings = Screening::latest()->limit(5)->get();

        if ($this->isMobile()) {
            return view('admin.dashboard.mobile_index', compact(
                'totalScreenings',
                'totalUsers',
                'totalRiskFactors',
                'totalRiskLevels',
                'totalRules',
                'riskCounts',
                'riskPercentages',
                'riskLevelColors',
                'latestScreenings'
            ));
        }

        return view('admin.dashboard', compact(
            'totalScreenings',
            'totalUsers',
            'totalRiskFactors',
            'totalRiskLevels',
            'totalRules',
            'riskCounts',
            'riskPercentages',
            'riskLevelColors',
            'latestScreenings'
        ));
    }
}