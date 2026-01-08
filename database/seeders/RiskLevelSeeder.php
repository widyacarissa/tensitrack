<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiskLevel;

class RiskLevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Kosongkan tabel agar bersih
        RiskLevel::truncate();

        $levels = [
            [
                'code'        => 'H01',
                'name'        => 'Tidak Berisiko',
                'description' => 'Kondisi kesehatan Anda sangat baik. Tidak ditemukan faktor risiko signifikan yang dapat memicu hipertensi saat ini.',
                'suggestion'  => 'Pertahankan gaya hidup sehat ini! Tetap rutin berolahraga dan konsumsi makanan bergizi seimbang.',
            ],
            [
                'code'        => 'H02',
                'name'        => 'Risiko Rendah',
                'description' => 'Teridentifikasi beberapa faktor risiko ringan. Meskipun kondisi ini belum mengkhawatirkan, sebaiknya tetap diperhatikan agar tidak bertambah di kemudian hari.',
                'suggestion'  => 'Tetap jaga pola hidup sehat Anda. Perbaiki beberapa kebiasaan kecil yang masih kurang baik sebagai investasi kesehatan jangka panjang.',
            ],
            [
                'code'        => 'H03',
                'name'        => 'Risiko Sedang',
                'description' => 'Ada beberapa indikasi atau kebiasaan harian yang terpantau bisa meningkatkan risiko hipertensi Anda jika tidak dikelola dengan baik.',
                'suggestion'  => 'Coba tinjau kembali pola makan dan tingkat stres Anda. Melakukan perubahan kecil secara konsisten sekarang akan sangat membantu menjaga tekanan darah tetap normal ke depannya.',
            ],
            [
                'code'        => 'H04',
                'name'        => 'Risiko Tinggi',
                'description' => 'Anda memiliki akumulasi faktor risiko yang tergolong tinggi. Meskipun mungkin belum ada gejala, kondisi ini menempatkan tubuh Anda bekerja lebih keras dari seharusnya, yang berpotensi memicu masalah kesehatan kardiovaskular di masa depan.',
                'suggestion'  => 'Sangat disarankan untuk melakukan konsultasi dengan tenaga medis sebagai langkah antisipasi. Mulailah perbaiki pola makan, aktivitas fisik, dan istirahat Anda dari sekarang demi kesehatan jangka panjang.',
            ],
        ];

        foreach ($levels as $level) {
            RiskLevel::create($level);
        }
    }
}