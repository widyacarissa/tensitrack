<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\KotaProvinsiController;
use App\Models\Diagnosis;
use App\Models\FaktorRisiko;
use App\Models\TingkatRisiko;
use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Http\Request as HttpRequest;

class BerandaController extends Controller
{
    public function index()
    {
        $data = [
            'loginDuration' => self::loginDuration(),
            'jumlahPengguna' => $this->jumlahPengguna(),
            'jumlahTingkatRisiko' => $this->jumlahTingkatRisiko(),
            'jumlahFaktorRisiko' => $this->jumlahFaktorRisiko(),
            'jumlahDiagnosis' => $this->jumlahDiagnosis(),
            'diagnosisTingkatRisiko' => $this->diagnosisTingkatRisiko(),
        ];

        return view('admin.beranda', $data);
    }

    public function jumlahPengguna()
    {
        $jumlahPengguna = User::count();

        return $jumlahPengguna;
    }

    public function jumlahTingkatRisiko()
    {
        $jumlahTingkatRisiko = TingkatRisiko::count();

        return $jumlahTingkatRisiko;
    }

    public function jumlahFaktorRisiko()
    {
        $jumlahFaktorRisiko = FaktorRisiko::count();

        return $jumlahFaktorRisiko;
    }

    public function jumlahDiagnosis()
    {
        $jumlahDiagnosis = Diagnosis::count();

        return $jumlahDiagnosis;
    }

    public function diagnosisTingkatRisiko()
    {
        $data = Diagnosis::selectRaw('count(*) as count, tingkat_risiko_id')->groupBy('tingkat_risiko_id')->get()->toArray();
        $tingkatRisiko = TingkatRisiko::get(['id', 'tingkat_risiko'])->toArray();
        $tingkatRisiko = array_column($tingkatRisiko, 'tingkat_risiko', 'id');
        $data = array_map(function ($item) use ($tingkatRisiko) {
            $item['tingkatRisiko'] = $tingkatRisiko[$item['tingkat_risiko_id']] ?? null;

            return $item;
        }, $data);

        return $data;
    }
}
