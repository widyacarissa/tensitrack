<?php

namespace App\Http\Controllers;

use App\Models\Diagnosis;
use App\Models\FaktorRisiko;
use App\Models\Rule;
use App\Models\TingkatRisiko;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $tingkatRisiko = TingkatRisiko::get(['id', 'name', 'reason', 'solution', 'image']);

        return view('user.user', compact('tingkatRisiko'));
    }

    public function historiDiagnosis(Request $request)
    {
        if ($request->isMethod('delete')) {
            $diagnosis = Diagnosis::find($request->id);
            $diagnosis->delete();

            return response()->json([
                'message' => 'Berhasil menghapus data',
            ]);
        }

        $user = auth()->user();

        $query = Diagnosis::with(['tingkatRisiko' => function ($query) {
            $query->select('id', 'name');
        }])->where('user_id', $user->id ?? null);

        if ($request->has('search.value')) {
            $searchValue = $request->input('search.value');
            $query->where(function ($q) use ($searchValue) {
                $q->where('id', 'like', '%'.$searchValue.'%')
                    ->orWhere('created_at', 'like', '%'.$searchValue.'%')
                    ->orWhereHas('tingkatRisiko', function ($q) use ($searchValue) {
                        $q->where('name', 'like', '%'.$searchValue.'%');
                    });
            });
        }

        $totalData = $query->count();

        $start = $request->input('start', 0);
        $length = $request->input('length', 5);

        $orderColumn = $request->input('order.0.column', 0);
        $orderDirection = $request->input('order.0.dir', 'asc');

        $orderColumns = [
            0 => 'id',
            1 => 'created_at',
        ];

        if (array_key_exists($orderColumn, $orderColumns)) {
            $orderBy = $orderColumns[$orderColumn];
            $query->orderBy($orderBy, $orderDirection);

            $no = ($orderDirection == 'asc') ? $totalData - $start : $start + 1;
        }

        $historiDiagnosis = $query
            ->offset($start)
            ->limit($length)
            ->get(['id', 'created_at', 'tingkat_risiko_id']);

        $data = $historiDiagnosis->map(function ($item) use (&$no, $orderDirection) {
            $tingkatRisiko = TingkatRisiko::find($item->tingkat_risiko_id, ['name']);
            $item->tingkatRisiko = $tingkatRisiko ? $tingkatRisiko->name : 'Tidak Diketahui';
            $item->no = ($orderDirection == 'asc') ? $no-- : $no++;

            return $item;
        });

        return response()->json([
            'draw' => $request->input('draw'),
            'recordsTotal' => $totalData,
            'recordsFiltered' => $totalData,
            'data' => $data,
        ]);
    }

    public function detailDiagnosis(Request $request)
    {
        $tingkatRisiko = TingkatRisiko::find(
            Diagnosis::find($request->id_diagnosis, ['tingkat_risiko_id'])->tingkat_risiko_id,
            ['name', 'reason', 'solution', 'image']
        );

        $diagnosis = Diagnosis::find($request->id_diagnosis, ['answer_log']);
        $answerLog = json_decode($diagnosis->answer_log, true);
        foreach ($answerLog as $key => $value) {
            $answerLog[$key] = $value == 1 ? 'Ya' : 'Tidak';
        }
        $faktorRisiko = FaktorRisiko::whereIn('id', array_keys($answerLog))->get(['id', 'name']);
        foreach ($faktorRisiko as $item) {
            $item->answer = $answerLog[$item->id];
        }
        $answerLog = $faktorRisiko->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'answer' => $item->answer,
            ];
        });

        return response()->json(
            [
                'tingkatRisiko' => $tingkatRisiko,
                'answerLog' => $answerLog,
            ]
        );
    }

    public function getFaktorRisiko()
    {
        $faktorRisiko = FaktorRisiko::get(['id', 'name', 'image']);

        return response()->json($faktorRisiko);
    }

    public function chartDiagnosisTingkatRisiko(Request $request)
    {
        // Mengumpulkan aturan-aturan berdasarkan tingkat risiko dan faktor risiko
        $rule = Rule::get(['tingkat_risiko_id', 'faktor_risiko_id']);
        $aturan = [];
        foreach ($rule as $value) {
            $aturan[$value->tingkat_risiko_id][] = $value->faktor_risiko_id;
        }

        // Mendapatkan data diagnosis dan log jawaban
        $diagnosis = Diagnosis::find($request->id_diagnosis, ['answer_log']);
        $answerLog = json_decode($diagnosis->answer_log, true);

        // Menghitung bobot untuk setiap tingkat risiko
        $bobot = [];
        foreach ($aturan as $idTingkatRisiko => $idFaktorRisiko) {
            $bobot[$idTingkatRisiko] = 0;
            foreach ($answerLog as $key => $value) {
                if (in_array($key, $idFaktorRisiko)) {
                    $bobot[$idTingkatRisiko] += $value;
                }
            }
        }

        // Menghitung persentase bobot untuk setiap tingkat risiko
        foreach ($bobot as $key => $value) {
            $jumlahFaktorRisiko = count($aturan[$key]);
            $bobot[$key] = ($jumlahFaktorRisiko > 0) ? round(($value / $jumlahFaktorRisiko) * 100, 2) : 0;
        }

        // Melakukan pemetaan bobot ke nama tingkat risiko
        $bobot = collect($bobot)->mapWithKeys(function ($item, $key) {
            $tingkatRisiko = TingkatRisiko::find($key, ['id', 'name']);

            return [$tingkatRisiko->name => $item];
        });

        return response()->json($bobot);
    }

    public function getRuleData()
    {
        $aturan = Rule::get(['tingkat_risiko_id', 'faktor_risiko_id'])->groupBy('tingkat_risiko_id')->map(function ($item) {
            return $item->pluck('faktor_risiko_id');
        });

        return response()->json($aturan);
    }
}
