<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Admin\FaktorRisikoController;
use App\Http\Controllers\Admin\HistoriDiagnosisController;
use App\Http\Controllers\Admin\RuleController;
use App\Http\Controllers\Admin\TingkatRisikoController;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;

class ShowPdfController extends Controller
{
    public function tingkatRisikoPdf()
    {
        $data = new TingkatRisikoController;
        $data = $data->index();
        $data = $data['tingkat_risiko'];
        $data = $data->toArray();
        foreach ($data as $key => $value) {
            $data[$key]['updated_at'] = Carbon::parse($value['updated_at'])->format('d-m-Y');
        }
        $data = ['tingkat_risiko' => $data];
        $pdf = Pdf::loadView('pdf.tingkat-risiko', $data);

        return $pdf->stream('tingkat_risiko_SPDHTC.pdf');
    }

    public function faktorRisikoPdf()
    {
        $data = new FaktorRisikoController;
        $data = $data->index();
        $data = $data['faktor_risiko'];
        $data = $data->toArray();
        foreach ($data as $key => $value) {
            $data[$key]['updated_at'] = Carbon::parse($value['updated_at'])->format('d-m-Y');
        }
        $data = ['faktor_risiko' => $data];
        $pdf = Pdf::loadView('pdf.faktor-risiko', $data);

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
