<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new UserProfile();
        
        // Hitung BMI & Kategori
        $bmi = 0;
        $bmi_category = '-';
        if ($profile->height && $profile->weight) {
            $h = $profile->height / 100;
            $bmi = round($profile->weight / ($h * $h), 1);
            if ($bmi < 18.5) $bmi_category = 'Kurus';
            elseif ($bmi <= 22.9) $bmi_category = 'Normal';
            elseif ($bmi <= 24.9) $bmi_category = 'Gemuk';
            else $bmi_category = 'Obesitas';
        }

        // Ambil Riwayat
        $history = \App\Models\Screening::where('user_id', $user->id)->latest()->get();
        
        // Ambil Latest Result (Tensi Terakhir dari Profil)
        $latest_result = '-';
        if ($profile->systolic && $profile->diastolic) {
            $latest_result = $profile->systolic . ' / ' . $profile->diastolic;
        }
        
        // Kategori Tensi (Logika Sederhana)
        $tensi_category = '-';
        if ($profile->systolic && $profile->diastolic) {
            $s = $profile->systolic;
            $d = $profile->diastolic;
            if ($s < 120 && $d < 80) $tensi_category = 'Normal';
            elseif ($s <= 139 || $d <= 89) $tensi_category = 'Normal Tinggi';
            else $tensi_category = 'Hipertensi';
        }

        if ($this->isMobile()) {
            return view('client.profile.mobile_index', compact(
                'user', 'profile', 'bmi', 'bmi_category', 
                'history', 'latest_result', 'tensi_category'
            ));
        }

        return view('client.profile.index', compact(
            'user', 'profile', 'bmi', 'bmi_category', 
            'history', 'latest_result', 'tensi_category'
        ));
    }

    public function update(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'age' => 'required|integer|min:1|max:120',
            'gender' => 'required|in:L,P',
            'height' => 'required|numeric|min:50|max:250',
            'weight' => 'required|numeric|min:20|max:300',
            'systolic' => 'nullable|integer|min:50|max:250',
            'diastolic' => 'nullable|integer|min:30|max:150',
        ]);

        $user = Auth::user();

        // Update atau Create profil
        $profile = UserProfile::updateOrCreate(
            ['user_id' => $user->id],
            [
                'full_name' => $request->full_name,
                'age' => $request->age,
                'gender' => $request->gender,
                'height' => $request->height,
                'weight' => $request->weight,
                'systolic' => $request->systolic,
                'diastolic' => $request->diastolic,
            ]
        );

        // Update nama di tabel users juga agar sinkron
        if ($user->name !== $request->full_name) {
            $user->update(['name' => $request->full_name]);
        }

        return redirect()->route('client.profile.index')->with('success', 'Profil berhasil diperbarui.');
    }

    public function detail($id)
    {
        $screening = \App\Models\Screening::with('details.riskFactor')->where('user_id', Auth::id())->findOrFail($id);
        
        // Sort details by Risk Factor Code (Ascending)
        $screening->setRelation('details', $screening->details->sortBy(function($detail) {
            return $detail->riskFactor->code;
        }));

        $riskLevel = \App\Models\RiskLevel::where('name', $screening->result_level)->first(); 
        $profile = UserProfile::where('user_id', Auth::id())->first();

        // Calculate BMI from Snapshot
        $bmi = 0;
        if ($screening->snapshot_height && $screening->snapshot_weight) {
            $h = $screening->snapshot_height / 100;
            $bmi = round($screening->snapshot_weight / ($h * $h), 1);
        }

        // Format Tensi
        $tensi = $screening->snapshot_systolic . '/' . $screening->snapshot_diastolic;

        if ($this->isMobile()) {
            return view('client.profile.mobile_detail', compact('screening', 'riskLevel', 'profile', 'bmi', 'tensi'));
        }

        return view('client.profile.detail', compact('screening', 'riskLevel', 'profile', 'bmi', 'tensi'));
    }

    public function printPdf($id, $action = 'view')
    {
        $screening = \App\Models\Screening::with('details.riskFactor')->where('user_id', Auth::id())->findOrFail($id);
        
        // Sort details by risk factor code ascending (E01 -> E12)
        $screening->setRelation('details', $screening->details->sortBy(function ($detail) {
            return $detail->riskFactor->code;
        })->values());

        $riskLevel = \App\Models\RiskLevel::where('name', $screening->result_level)->first(); 

        $pdf = Pdf::loadView('client.profile.pdf', compact('screening', 'riskLevel'));
        
        if ($action == 'download') {
            return $pdf->download('hasil-skrining-' . $id . '.pdf');
        }
        return $pdf->stream('hasil-skrining-' . $id . '.pdf');
    }
}
