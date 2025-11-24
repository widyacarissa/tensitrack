<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FaktorRisiko;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FaktorRisikoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $faktorRisiko = FaktorRisiko::get(['id', 'kode', 'name', 'updated_at']);

        return view('admin.faktor-risiko.faktor-risiko', compact('faktorRisiko'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.faktor-risiko.tambah');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'faktorRisiko' => 'required|string',
        ]);

        // Automatic Kode Generation
        $latestFaktorRisiko = FaktorRisiko::orderBy('id', 'desc')->first();
        if ($latestFaktorRisiko) {
            $lastKode = $latestFaktorRisiko->kode;
            $number = (int) substr($lastKode, 1);
            $newNumber = $number + 1;
            $newKode = 'E' . str_pad($newNumber, 2, '0', STR_PAD_LEFT);
        } else {
            $newKode = 'E01';
        }

        $form_data = [
            'kode' => $newKode,
            'name' => $request->faktorRisiko,
        ];

        FaktorRisiko::create($form_data);

        return redirect(route('admin.faktor-risiko'))->with('success', 'Data berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $faktorRisiko = FaktorRisiko::findOrFail($id);

        return view('admin.faktor-risiko.edit', compact('faktorRisiko'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $faktorRisiko = FaktorRisiko::findOrFail($id);

        $this->validate($request, [
            'faktorRisiko' => 'required|string',
        ]);

        $form_data = [
            'name' => $request->faktorRisiko,
        ];

        $faktorRisiko->update($form_data);

        return redirect(route('admin.faktor-risiko'))->with('success', 'Data berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $faktorRisiko = FaktorRisiko::findOrFail($id);

        try {
            $faktorRisiko->delete();
        } catch (QueryException $q) {
            if ($q->getCode() == 23000) {
                return redirect()->route('admin.faktor-risiko')->with('error', 'Data tidak dapat dihapus karena sedang digunakan!');
            }
        }

        return redirect(route('admin.faktor-risiko'))->with('success', 'Data berhasil dihapus!');
    }
}
