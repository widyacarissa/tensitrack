<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Models\FaktorRisiko;
use App\Models\Rule;
use App\Models\TingkatRisiko;
use Illuminate\Http\Request;

class DiagnosisController extends Controller
{
    private $allFaktorRisiko;

    public function __construct()
    {
        $this->allFaktorRisiko = FaktorRisiko::get('id')->count();
    }

    private function newDiagnosis()
    {
        $modelDiagnosis = new Diagnosis;
        $modelDiagnosis->user_id = auth()->user()->id;

        return $modelDiagnosis;
    }

    private function lastDiagnosis()
    {
        return Diagnosis::where('user_id', auth()->user()->id)->get()->last();
    }

    private function checkDiagnosis($id_faktor_risiko)
    {
        $lastDiagnosis = $this->lastDiagnosis();

        if ($id_faktor_risiko === 1) {
            return $this->newDiagnosis();
        }

        if ($lastDiagnosis->tingkat_risiko_id === null) {
            $answerLog = json_decode($lastDiagnosis->answer_log, true) ?? [];
            $maxAnswerLog = max(array_keys($answerLog));

            if ($maxAnswerLog === $this->allFaktorRisiko) {
                return $this->newDiagnosis();
            }

            return $lastDiagnosis;
        }

        return $this->newDiagnosis();
    }

    public function diagnosis(Request $request)
    {
        $request->validate([
            'id_faktor_risiko' => ['required', 'numeric', 'max:'.$this->allFaktorRisiko, 'min:1'],
        ]);

        $requestFakta = [
            $request->id_faktor_risiko => filter_var($request->value, FILTER_VALIDATE_BOOLEAN),
        ];

        $modelDiagnosis = $this->checkDiagnosis((int) $request->id_faktor_risiko);
        $answerLog = json_decode($modelDiagnosis->answer_log, true) ?? [];
        $answerLog = $answerLog + $requestFakta;
        $modelDiagnosis->answer_log = json_encode($answerLog);
        $modelDiagnosis->save();

        // Aturan
        $rule = Rule::get(['tingkat_risiko_id', 'faktor_risiko_id']);
        $aturan = [];
        foreach ($rule as $key => $value) {
            $aturan[$value->tingkat_risiko_id][] = $value->faktor_risiko_id;
        }

        // Basis Fakta
        $fakta = $answerLog;

        // Inferensi
        $terdeteksi = false;
        foreach ($aturan as $tingkatRisikoId => $faktorRisiko) {
            $apakahTingkatRisiko = true;
            foreach ($faktorRisiko as $idFaktorRisiko) {
                $fakta[$idFaktorRisiko] = $fakta[$idFaktorRisiko] ?? false;
                if (! $fakta[$idFaktorRisiko]) {
                    $apakahTingkatRisiko = false;
                    break;
                }
            }
            if ($apakahTingkatRisiko) {
                if ($modelDiagnosis->tingkat_risiko_id == null) {
                    $modelDiagnosis->tingkat_risiko_id = $tingkatRisikoId;
                    $modelDiagnosis->save();
                }
                $tingkatRisiko = TingkatRisiko::where('id', $modelDiagnosis->tingkat_risiko_id)->first('id');
                $terdeteksi = true;
            }
        }

        // Tidak ada tingkat risiko yang terdeteksi
        if (! $terdeteksi && $request->id_faktor_risiko == $this->allFaktorRisiko) {
            return response()->json([
                'tingkatRisikoUnidentified' => true,
                'idTingkatRisiko' => null,
                'idDiagnosis' => $modelDiagnosis->id,
            ]);
        }

        return response()->json([
            'idDiagnosis' => $modelDiagnosis->id,
            'idTingkatRisiko' => $tingkatRisiko ?? null,
        ]);
    }
}
