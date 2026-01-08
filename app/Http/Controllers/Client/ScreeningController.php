<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\RiskFactor;
use App\Models\RiskLevel;
use App\Models\Rule;
use App\Models\Screening;
use App\Models\ScreeningDetail;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScreeningController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $profile = UserProfile::where('user_id', $userId)->first();

        // Daftar kode yang akan di-exclude (dihilangkan dari kuis)
        $excludedCodes = [];

        // Cek Tensi di Profil
        // Jika data tensi lengkap, hitung otomatis (exclude E01 dari kuis)
        if ($profile && $profile->systolic && $profile->diastolic) {
            $excludedCodes[] = 'E01';
        }

        // Cek BMI di Profil (E08)
        // Jika TB/BB ada, hitung otomatis (exclude E08 dari kuis)
        if ($profile && $profile->height && $profile->weight) {
            $excludedCodes[] = 'E08';
        }

        // Ambil faktor risiko selain yang di-exclude
        $factors = RiskFactor::whereNotIn('code', $excludedCodes)->orderBy('code', 'asc')->get();

        // Cek Kelengkapan Profil (Nama, Umur, Gender Wajib)
        $isProfileComplete = $profile && $profile->full_name && $profile->age && $profile->gender;

        if ($this->isMobile()) {
            return view('client.screening.mobile_index', compact('factors', 'isProfileComplete'));
        }

        return view('client.screening.index', compact('factors', 'isProfileComplete'));
    }

    public function result(Request $request)
    {
        try {
            $userId = Auth::id();
            $profile = UserProfile::where('user_id', $userId)->first();

            $answers = $request->input('answers', []); // Array ID faktor yang dijawab YA

            // Casting ke integer
            $answers = array_map('intval', $answers);

            $autoFactors = [];

            // 1. Cek Faktor Otomatis (E01 & E03)
            if ($profile) {
                // Cek E01 (Tensi): Hanya otomatis jika data profil ada.
                if ($profile->systolic && $profile->diastolic) {
                    $sys = $profile->systolic;
                    $dia = $profile->diastolic;
                    // Cek E01 (Tensi): Pre-Hipertensi atau lebih tinggi (>= 121/81)
                    if (($sys >= 121) || ($dia >= 81)) {
                        $e01 = RiskFactor::where('code', 'E01')->first();
                        if ($e01) {
                            $autoFactors[] = (int) $e01->id;
                        }
                    }
                }

                // Cek E08 (BMI): Selalu otomatis dari profil
                if ($profile->height && $profile->weight) {
                    $h = $profile->height / 100;
                    $bmi = $profile->weight / ($h * $h);
                    if ($bmi >= 25) {
                        $e08 = RiskFactor::where('code', 'E08')->first();
                        if ($e08) {
                            $autoFactors[] = (int) $e08->id;
                        }
                    }
                }
            }

            // Gabungkan jawaban manual user dan otomatis
            $finalFactors = array_unique(array_merge($answers, $autoFactors));

            // 2. Jalankan Engine Diagnosa
            $e01Ref = RiskFactor::where('code', 'E01')->first();
            $e01RefId = $e01Ref ? (int) $e01Ref->id : null;

            $diagnosis = $this->runDiagnosis($finalFactors, $e01RefId);

            // 3. Simpan Hasil
            $screening = Screening::create([
                'user_id' => $userId,
                'client_name' => $profile ? $profile->full_name : Auth::user()->name,
                'snapshot_age' => $profile->age ?? 0,
                'snapshot_height' => $profile->height ?? 0,
                'snapshot_weight' => $profile->weight ?? 0,
                'snapshot_systolic' => $profile->systolic ?? 0,
                'snapshot_diastolic' => $profile->diastolic ?? 0,
                'result_level' => $diagnosis->name,
                'score' => count($finalFactors),
            ]);

            // 4. Simpan Detail Jawaban
            foreach ($finalFactors as $factorId) {
                ScreeningDetail::create([
                    'screening_id' => $screening->id,
                    'risk_factor_id' => $factorId,
                ]);
            }

            return redirect()->route('client.profile.detail', $screening->id);

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    private function runDiagnosis($selectedFactorIds, $tensiFactorId = null)
    {
        // Ambil ID untuk E01 dan E02 sebagai faktor spesial
        $e01 = RiskFactor::where('code', 'E01')->first();
        $e02 = RiskFactor::where('code', 'E02')->first();
        
        $specialFactorIds = [];
        if ($e01) $specialFactorIds[] = $e01->id;
        if ($e02) $specialFactorIds[] = $e02->id;

        // Ambil semua aturan beserta faktor wajibnya, urutkan prioritas
        $rules = Rule::with('riskFactors')->orderBy('priority', 'ASC')->get();

        foreach ($rules as $rule) {
            $requiredFactorIds = $rule->riskFactors->pluck('id')->toArray();
            
            // CEK LOGIKA UTAMA (Required Factors)
            if ($rule->operator === 'OR' && !empty($requiredFactorIds)) {
                // LOGIKA OR: Harus punya MINIMAL SATU dari faktor wajib
                $hasAny = !empty(array_intersect($requiredFactorIds, $selectedFactorIds));
                if (!$hasAny) {
                    continue; // Gagal syarat OR
                }
            } else {
                // LOGIKA AND (Default): Harus punya SEMUA faktor wajib
                $missingFactors = array_diff($requiredFactorIds, $selectedFactorIds);
                if (!empty($missingFactors)) {
                    continue; // Gagal syarat AND
                }
            }

            // CEK LOGIKA FAKTOR LAIN (E03 - E12)
            // Definisi: Faktor lain adalah faktor user dikurangi faktor spesial (E01 & E02)
            $otherFactors = array_diff($selectedFactorIds, $specialFactorIds);
            $count = count($otherFactors);

            // Cek Range Jumlah Faktor Lain
            if ($count >= $rule->min_other_factors && $count <= $rule->max_other_factors) {
                return RiskLevel::find($rule->risk_level_id);
            }
        }

        // Default: Tidak Berisiko (H01)
        $default = RiskLevel::where('code', 'H01')->first();

        return $default ? $default : (object) ['name' => 'Risiko Tidak Diketahui'];
    }
}
