<?php

namespace App\Http\Controllers;

use App\Models\FaktorRisiko;
use App\Models\TingkatRisiko;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ShowPdfController extends Controller
{
    public function tingkatRisikoPdf()
    {
        $tingkatRisiko = TingkatRisiko::all();
        foreach ($tingkatRisiko as $key => $value) {
            $value->updated_at_formatted = Carbon::parse($value->updated_at)->format('d-m-Y');
        }
        $pdf = Pdf::loadView('pdf.tingkat-risiko', compact('tingkatRisiko'));

        return $pdf->stream('tingkat_risiko_SPDHTC.pdf');
    }

    public function faktorRisikoPdf()
    {
        $faktorRisiko = FaktorRisiko::all();
        foreach ($faktorRisiko as $key => $value) {
            $value->updated_at_formatted = Carbon::parse($value->updated_at)->format('d-m-Y');
        }
        $pdf = Pdf::loadView('pdf.faktor-risiko', compact('faktorRisiko'));

        return $pdf->stream('faktor_risiko_SPDHTC.pdf');
    }

    public function rulePdf()
    {
        $data = new RuleController;
        $data = $data->index();
        $data = $data['rules'];
        foreach ($data as $key => $value) {
            $data[$key]['updated_at'] = Carbon::parse($value['updated_at'])->format('d-m-Y');
        }
        $data = ['rules' => $data];
        $pdf = Pdf::loadView('pdf.rule', $data);

        return $pdf->stream('rule_SPDHTC.pdf');
    }

    public function historiDiagnosisPdf()
    {
        $data = new HistoriDiagnosisController;
        $data = $data->index();
        $data = $data['diagnosis'];
        foreach ($data as $key => $value) {
            $data[$key]['updated_at'] = Carbon::parse($value['updated_at'])->format('d-m-Y');
        }
        $data = ['historiDiagnosis' => $data];
        $pdf = Pdf::loadView('pdf.histori-diagnosis', $data);

        return $pdf->stream('histori-diagnosis_SPDHTC.pdf');
    }
}
