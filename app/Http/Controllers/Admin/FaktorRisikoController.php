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
        $faktorRisiko = FaktorRisiko::get(['id', 'name', 'image', 'updated_at']);

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
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // upload image
        $image = $request->file('image');
        $new_name = rand().'.'.$image->getClientOriginalExtension();
        $image->storeAs('public/faktor-risiko', $new_name);

        $form_data = [
            'name' => $request->faktorRisiko,
            'image' => $new_name,
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
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $old_image = $faktorRisiko->image;
            $image_path = 'public/faktor-risiko/'.$old_image;

            if (file_exists($image_path)) {
                unlink($image_path);
            }

            $image = $request->file('image');
            $new_name = rand().'.'.$image->getClientOriginalExtension();
            $image->storeAs('public/faktor-risiko', $new_name);
        }

        $form_data = [
            'name' => $request->faktorRisiko,
            'image' => $new_name ?? $faktorRisiko->image,
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
            if ($faktorRisiko->delete()) {
                Storage::delete('public/faktor-risiko/'.$faktorRisiko->image);
            }
        } catch (QueryException $q) {
            if ($q->getCode() == 23000) {
                return redirect()->route('admin.faktor-risiko')->with('error', 'Data tidak dapat dihapus karena sedang digunakan!');
            }
        }

        return redirect(route('admin.faktor-risiko'))->with('success', 'Data berhasil dihapus!');
    }
}
