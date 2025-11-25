<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Services\DiagnosisService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiagnosisController extends Controller
{
    protected $diagnosisService;

    public function __construct(DiagnosisService $diagnosisService)
    {
        $this->diagnosisService = $diagnosisService;
    }

    /**
     * Receives all selected risk factors at once and calculates the final risk level.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function calculate(Request $request)
    {
        $request->validate([
            'selected_factors' => 'present|array',
            'selected_factors.*' => 'integer|exists:faktor_risiko,id',
        ]);

        $submittedFaktorIds = $request->input('selected_factors');

        // Use the service to calculate the risk
        $tingkatRisiko = $this->diagnosisService->calculateRisk($submittedFaktorIds);

        // Create a new diagnosis record
        $diagnosis = new Diagnosis();
        $diagnosis->user_id = Auth::id();
        $diagnosis->answer_log = json_encode($submittedFaktorIds); // Store the array of submitted factor IDs
        
        if ($tingkatRisiko) {
            $diagnosis->tingkat_risiko_id = $tingkatRisiko->id;
            $diagnosis->save();

            return response()->json([
                'success' => true,
                'tingkatRisikoUnidentified' => false,
                'idTingkatRisiko' => $tingkatRisiko->id,
                'idDiagnosis' => $diagnosis->id,
            ]);
        } else {
            $diagnosis->tingkat_risiko_id = null;
            $diagnosis->save();
            
            return response()->json([
                'success' => true,
                'tingkatRisikoUnidentified' => true,
                'idTingkatRisiko' => null,
                'idDiagnosis' => $diagnosis->id,
            ]);
        }
    }
}