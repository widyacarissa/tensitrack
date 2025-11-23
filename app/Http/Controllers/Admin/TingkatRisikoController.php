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
            'tingkat_risiko' => TingkatRisiko::get(['id', 'name', 'reason', 'solution', 'image', 'updated_at']),
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
            'reason' => 'required|string',
            'solution' => 'required|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // upload image
        $image = $request->file('image');
        $new_name = rand().'.'.$image->getClientOriginalExtension();
        $image->storeAs('public/tingkat_risiko', $new_name);

        $form_data = [
            'name' => $request->tingkat_risiko,
            'reason' => $request->reason,
            'solution' => $request->solution,
            'image' => $new_name,
        ];

        TingkatRisiko::create($form_data);

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
            'reason' => 'required|string',
            'solution' => 'required|string',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $old_image = $tingkat_risiko->image;
            $image_path = 'public/tingkat_risiko/'.$old_image;
            if (file_exists($image_path)) {
                unlink($image_path);
            }
            $image = $request->file('image');
            $new_name = rand().'.'.$image->getClientOriginalExtension();
            $image->storeAs('public/tingkat_risiko', $new_name);
        }

        $form_data = [
            'name' => $request->tingkat_risiko,
            'reason' => $request->reason,
            'solution' => $request->solution,
            'image' => $new_name ?? $tingkat_risiko->image,
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
                Storage::delete('public/tingkat_risiko/'.$tingkat_risiko->image);
            }
        } catch (QueryException $e) {
            if ($e->getCode() == 23000) {
                return redirect()->route('admin.tingkat_risiko')->with('error', 'Data tidak dapat dihapus karena sedang digunakan!');
            }
        }

        return redirect(route('admin.tingkat_risiko'))->with('success', 'Data berhasil dihapus!');
    }
}
