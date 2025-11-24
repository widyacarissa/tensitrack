<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TingkatRisiko;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TingkatRisikoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = [
            'tingkat_risiko' => TingkatRisiko::get(['id', 'kode', 'tingkat_risiko', 'keterangan', 'saran', 'updated_at']),
        ];

        return view('admin.tingkat_risiko.tingkat_risiko', $data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tingkat_risiko.tambah');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'tingkat_risiko' => 'required|string',
            'keterangan' => 'required|string',
            'saran' => 'required|string',
        ]);

        // Automatic Kode Generation
        $latestTingkatRisiko = TingkatRisiko::orderBy('id', 'desc')->first();
        if ($latestTingkatRisiko) {
            $lastKode = $latestTingkatRisiko->kode;
            $number = (int) substr($lastKode, 1);
            $newNumber = $number + 1;
            $newKode = 'H' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
        } else {
            $newKode = 'R01';
        }

        TingkatRisiko::create([
            'kode' => $newKode,
            'tingkat_risiko' => $request->tingkat_risiko,
            'keterangan' => $request->keterangan,
            'saran' => $request->saran,
        ]);

        return redirect(route('admin.tingkat_risiko'))->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $tingkat_risiko = TingkatRisiko::findOrFail($id);

        return view('admin.tingkat_risiko.edit', compact('tingkat_risiko'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tingkat_risiko = TingkatRisiko::findOrFail($id);

        $this->validate($request, [
            'tingkat_risiko' => 'required|string',
            'keterangan' => 'required|string',
            'saran' => 'required|string',
        ]);

        $form_data = [
            'tingkat_risiko' => $request->tingkat_risiko,
            'keterangan' => $request->keterangan,
            'saran' => $request->saran,
        ];

        $tingkat_risiko->update($form_data);

        return redirect(route('admin.tingkat_risiko'))->with('success', 'Data berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tingkat_risiko = TingkatRisiko::findOrFail($id);

        try {
            if ($tingkat_risiko->delete()) {
                // Image column removed, no need to delete image file.
            }
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->route('admin.tingkat_risiko')->with('error', 'Data tidak dapat dihapus karena sedang digunakan!');
            }
        }

        return redirect(route('admin.tingkat_risiko'))->with('success', 'Data berhasil dihapus!');
    }
}
