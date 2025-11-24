<?php

namespace Database\Seeders;

use App\Models\FaktorRisiko;
use Illuminate\Database\Seeder;

class FaktorRisikoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = [
            [
                'kode' => 'E01',
                'name' => 'Tekanan darah yang terukur mengalami peningkatan (sistolik 120–139 mmHg) dan/atau (diastolik 80–89 mmHg)',
            ],
            [
                'kode' => 'E02',
                'name' => 'Terdapat anggota keluarga inti (ayah, ibu, kakak/adik) yang memiliki riwayat penyakit tekanan darah tinggi (hipertensi)',
            ],
            [
                'kode' => 'E03',
                'name' => 'Indeks massa tubuh termasuk dalam kategori obesitas (IMT ≥ 25)',
            ],
            [
                'kode' => 'E04',
                'name' => 'Memiliki kebiasaan merokok (tembakau/elektrik)',
            ],
            [
                'kode' => 'E05',
                'name' => 'Memiliki kebiasaan mengonsumsi minuman beralkohol',
            ],
            [
                'kode' => 'E06',
                'name' => 'Memiliki kebiasaan mengonsumsi minuman berenergi seperti kratingdeng, extrajoss',
            ],
            [
                'kode' => 'E07',
                'name' => 'Memiliki kebiasaan mengonsumsi minuman kafein seperti kopi atau teh kental',
            ],
            [
                'kode' => 'E08',
                'name' => 'Memiliki kebiasaan mengonsumsi makanan tinggi garam dan lemak seperti mie instan, chiki, keripik, gorengan, sosis, nugget, basreng',
            ],
            [
                'kode' => 'E09',
                'name' => 'Jarang melakukan aktivitas fisik atau berolahraga seperti jogging, senam, bersepeda',
            ],
            [
                'kode' => 'E10',
                'name' => 'Memiliki pola istirahat yang tidak teratur (sering begadang)',
            ],
            [
                'kode' => 'E11',
                'name' => 'Sering merasa stres ekstrim, tertekan, atau cemas berlebih',
            ],
        ];

        foreach ($rows as $row) {
            FaktorRisiko::updateOrCreate($row);
        }
    }
}
