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
        $tingkatRisiko = TingkatRisiko::get(['id', 'kode', 'tingkat_risiko', 'keterangan', 'saran']);

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
            $query->select('id', 'tingkat_risiko');
        }])->where('user_id', $user->id ?? null);

        if ($request->has('search.value')) {
            $searchValue = $request->input('search.value');
            $query->where(function ($q) use ($searchValue) {
                $q->where('id', 'like', '%'.$searchValue.'%')
                    ->orWhere('created_at', 'like', '%'.$searchValue.'%')
                    ->orWhereHas('tingkatRisiko', function ($q) use ($searchValue) {
                        $q->where('tingkat_risiko', 'like', '%'.$searchValue.'%');
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
            $tingkatRisiko = TingkatRisiko::find($item->tingkat_risiko_id, ['tingkat_risiko']);
            $item->tingkatRisiko = $tingkatRisiko ? $tingkatRisiko->tingkat_risiko : 'Tidak Diketahui';
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
        $diagnosis = Diagnosis::findOrFail($request->id_diagnosis);
        $tingkatRisiko = TingkatRisiko::find($diagnosis->tingkat_risiko_id, ['kode', 'tingkat_risiko', 'keterangan', 'saran']);

        $selectedFactorIds = json_decode($diagnosis->answer_log, true) ?? [];
        
        // Get all factors and determine the answer for each
        $allFaktorRisiko = FaktorRisiko::orderBy('id')->get(['id', 'name'])->map(function ($item) use ($selectedFactorIds) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'answer' => in_array($item->id, $selectedFactorIds) ? 'Ya' : 'Tidak',
            ];
        });

        return response()->json(
            [
                'tingkatRisiko' => $tingkatRisiko,
                'answerLog' => $allFaktorRisiko,
            ]
        );
    }

    public function getFaktorRisiko()
    {
        $faktorRisiko = FaktorRisiko::get(['id', 'name']);

        return response()->json($faktorRisiko);
    }

    public function chartDiagnosisTingkatRisiko(Request $request)
    {
        $diagnosis = Diagnosis::findOrFail($request->id_diagnosis);
        $allTingkatRisiko = TingkatRisiko::all(['id', 'tingkat_risiko']);
        
        $bobot = [];
        foreach ($allTingkatRisiko as $tingkat) {
            // If this is the diagnosed risk level, set percentage to 100, otherwise 0.
            $percentage = ($tingkat->id == $diagnosis->tingkat_risiko_id) ? 100 : 0;
            $bobot[$tingkat->tingkat_risiko] = $percentage;
        }

        return response()->json($bobot);
    }
}
