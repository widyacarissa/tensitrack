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
            'chartProvince' => $this->chartProvince(),
            'chartCity' => $this->chartCity(),
            'chartProfession' => $this->chartProfession(),
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

    public function chartProvince()
    {
        $data = UserProfile::selectRaw('count(*) as count, province')
            ->groupBy('province')
            ->get()->toArray();
        $indexProvince = new KotaProvinsiController;
        $provinces = $indexProvince->indexProvince();
        $provinces = json_decode(json_encode($provinces), true);

        $province = [];
        foreach ($provinces as $key => $value) {
            $province[$value['province_id']] = [
                'province' => $value['province'],
            ];
        }

        $data = array_map(function ($item) use ($province) {
            $item['province'] = $province[$item['province']]['province'] ?? null;

            return $item;
        }, $data);

        return $data;
    }

    public function chartCity()
    {
        $data = UserProfile::selectRaw('count(*) as count, city')->groupBy('city')->get()->toArray();

        $userProfileCity = array_column($data, 'city'); // Mengambil semua id dari hasil query
        $userProfiles = UserProfile::whereIn('city', $userProfileCity)->get('province')->toArray();
        $indexCity = new KotaProvinsiController;

        $request = new HttpRequest;

        $cities = [];
        foreach ($userProfiles as $key => $value) {
            $value['province'] = $indexCity->indexCity($request, $value['province']);
            $value['province'] = json_decode(json_encode($value['province']), true);
            foreach ($value['province'] as $key2 => $value2) {
                $cities[$value2['city_id']] = [
                    'city' => $value2['city_name'],
                ];
            }
        }
        $data = array_map(function ($item) use ($cities) {
            $item['city'] = $cities[$item['city']]['city'] ?? null;

            return $item;
        }, $data);

        return $data;
    }

    public function chartProfession()
    {
        $data = UserProfile::selectRaw('count(*) as count, profession')->groupBy('profession')->get()->toArray();

        return $data;
    }

    public function diagnosisTingkatRisiko()
    {
        $data = Diagnosis::selectRaw('count(*) as count, tingkat_risiko_id')->groupBy('tingkat_risiko_id')->get()->toArray();
        $tingkatRisiko = TingkatRisiko::get(['id', 'name'])->toArray();
        $tingkatRisiko = array_column($tingkatRisiko, 'name', 'id');
        $data = array_map(function ($item) use ($tingkatRisiko) {
            $item['tingkatRisiko'] = $tingkatRisiko[$item['tingkat_risiko_id']] ?? null;

            return $item;
        }, $data);

        return $data;
    }
}
