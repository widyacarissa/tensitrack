<?php

namespace Database\Seeders;

use App\Models\TingkatRisiko;
use Illuminate\Database\Seeder;

class TingkatRisikoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rows = [
            [
                'tingkat_risiko' => 'Hipertensi Ringan',
                'keterangan' => 'Tekanan darah sistolik 130-139 mmHg atau diastolik 80-89 mmHg.',
                'saran' => implode("\n", [
                    '1. Rutin berolahraga setidaknya 30 menit setiap hari, 5 hari seminggu.',
                    '2. Konsumsi makanan sehat, batasi garam, dan hindari makanan olahan.',
                    '3. Kelola stres dengan meditasi, yoga, atau hobi yang menenangkan.',
                    '4. Hindari merokok dan batasi konsumsi alkohol.',
                    '5. Pantau tekanan darah secara teratur.',
                ]),
            ],
            [
                'tingkat_risiko' => 'Hipertensi Sedang',
                'keterangan' => 'Tekanan darah sistolik 140-159 mmHg atau diastolik 90-99 mmHg.',
                'saran' => implode("\n", [
                    '1. Ikuti anjuran dokter untuk pengobatan dan perubahan gaya hidup.',
                    '2. Jaga berat badan ideal, karena obesitas meningkatkan risiko hipertensi.',
                    '3. Lakukan pemeriksaan kesehatan rutin.',
                    '4. Konsultasi dengan ahli gizi untuk diet yang tepat.',
                    '5. Kurangi kafein.',
                ]),
            ],
            [
                'tingkat_risiko' => 'Hipertensi Berat',
                'keterangan' => 'Tekanan darah sistolik ≥160 mmHg atau diastolik ≥100 mmHg.',
                'saran' => implode("\n", [
                    '1. Segera cari pertolongan medis dan ikuti rencana perawatan darurat.',
                    '2. Patuhi pengobatan secara ketat dan jangan lewatkan dosis.',
                    '3. Batasi aktivitas fisik berat sementara waktu.',
                    '4. Hindari semua bentuk stimulan seperti kafein dan nikotin.',
                    '5. Perlu pemantauan ketat oleh tim medis.',
                ]),
            ],
        ];

        foreach ($rows as $index => $row) {
            TingkatRisiko::updateOrCreate(
                ['tingkat_risiko' => $row['tingkat_risiko']],
                [
                    'kode' => 'H' . str_pad($index + 1, 2, '0', STR_PAD_LEFT),
                    'keterangan' => $row['keterangan'],
                    'saran' => $row['saran'],
                ]
            );
        }
    }
}
