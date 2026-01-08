<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RiskFactor;

class RiskFactorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pastikan tabel kosong sebelum diisi ulang agar tidak duplikat
        RiskFactor::truncate();

        $factors = [
            [
                'code' => 'E01',
                'name' => 'Tekanan darah meningkat (121–139 / 81–89 mmHg)',
                'question_text' => 'Apakah hasil pengukuran tekanan darah anda mengalami peningkatan (Sistolik 121–139 mmHg atau Diastolik 81–89 mmHg)?',
                'medical_explanation' => 'Tekanan darah pre-hipertensi adalah sinyal peringatan dini. Pembuluh darah Anda mulai mengalami tekanan berlebih yang jika dibiarkan akan merusak organ vital.',
                'recommendation' => 'Lakukan pemantauan tekanan darah rutin setiap minggu. Mulai kurangi konsumsi garam < 1 sendok teh per hari.',
            ],
            [
                'code' => 'E02',
                'name' => 'Riwayat hipertensi pada keluarga inti',
                'question_text' => 'Apakah ada anggota keluarga inti anda (ayah, ibu, kakak/adik) yang memiliki riwayat tekanan darah tinggi?',
                'medical_explanation' => 'Faktor genetik membuat tubuh Anda lebih sensitif terhadap garam dan stres. Risiko Anda terkena hipertensi 2-3x lebih tinggi dibanding orang tanpa riwayat keluarga.',
                'recommendation' => 'Karena Anda memiliki faktor keturunan, Anda perlu lebih waspada dalam menjaga pola hidup sehat. Upayakan untuk menjaga berat badan tetap ideal karena obesitas akan melipatgandakan risiko hipertensi pada orang dengan riwayat keluarga.',
            ],
            [
                'code' => 'E03',
                'name' => 'Konsumsi Tinggi Garam (Asin) & Berbumbu',
                'question_text' => 'Apakah anda sering mengonsumsi makanan tinggi garam & berbumbu seperti mie instan, chiki, keripik, sosis, nugget, basreng, kerupuk (lebih dari 3 kali dalam seminggu)?',
                'medical_explanation' => 'Garam (Natrium) bersifat menarik air. Terlalu banyak garam membuat volume darah meningkat sehingga tekanan darah naik.',
                'recommendation' => 'Kurangi penggunaan garam dapur, saus sambal, dan kecap. Hindari makanan kemasan/kalengan. Perbanyak bumbu rempah alami.',
            ],
            [
                'code' => 'E04',
                'name' => 'Stres & cemas berlebih',
                'question_text' => 'Apakah anda sering merasa stres, tertekan, atau cemas berlebih dalam sebulan terakhir?',
                'medical_explanation' => 'Stres kronis membanjiri tubuh dengan hormon kortisol dan adrenalin yang mempercepat detak jantung dan menyempitkan pembuluh darah.',
                'recommendation' => 'Luangkan waktu 15 menit sehari untuk relaksasi, meditasi, atau hobi. Jangan ragu curhat ke teman atau profesional jika beban terasa berat.',
            ],
            [
                'code' => 'E05',
                'name' => 'Konsumsi Minuman Kafein Berlebih (Kopi)',
                'question_text' => 'Apakah anda minum kopi lebih dari 2 gelas sehari?',
                'medical_explanation' => 'Kafein memblokir hormon yang menjaga pembuluh darah tetap lebar, menyebabkan penyempitan sementara.',
                'recommendation' => 'Batasi kopi maksimal 1-2 cangkir per hari. Pilih kopi rendah kafein (decaf) jika memungkinkan.',
            ],
            [
                'code' => 'E06',
                'name' => 'Pola Tidur Buruk / Begadang',
                'question_text' => 'Apakah anda sering tidur larut malam atau tidur kurang dari 6–7 jam sehari?',
                'medical_explanation' => 'Saat tidur, tekanan darah tubuh seharusnya turun (dipping). Kurang tidur membuat tekanan darah terus tinggi selama 24 jam.',
                'recommendation' => 'Terapkan "Sleep Hygiene": Matikan gadget 1 jam sebelum tidur, gelapkan kamar, dan usahakan tidur & bangun di jam yang sama.',
            ],
            [
                'code' => 'E07',
                'name' => 'Kebiasaan Merokok',
                'question_text' => 'Apakah anda merokok aktif (tembakau/elektrik) hampir setiap hari dalam sebulan terakhir?',
                'medical_explanation' => 'Nikotin menyebabkan penyempitan pembuluh darah seketika dan merusak dinding arteri secara permanen. Vape juga mengandung zat kimia berbahaya bagi jantung.',
                'recommendation' => 'Berhenti merokok adalah langkah terbaik. Jika sulit, kurangi jumlah batang rokok secara bertahap. Hindari merokok saat bangun tidur.',
            ],
            [
                'code' => 'E08',
                'name' => 'Obesitas (IMT ≥ 25)',
                'question_text' => 'Berdasarkan perhitungan Indeks Massa Tubuh (IMT), apakah anda termasuk dalam kategori obesitas (IMT ≥ 25)?',
                'medical_explanation' => 'Lemak tubuh berlebih, terutama di perut, memproduksi hormon yang mengeraskan pembuluh darah dan meningkatkan resistensi insulin.',
                'recommendation' => 'Targetkan penurunan berat badan 5-10% dalam 3 bulan ke depan. Fokus pada diet defisit kalori dan jalan kaki minimal 30 menit sehari.',
            ],
            [
                'code' => 'E09',
                'name' => 'Konsumsi Alkohol',
                'question_text' => 'Apakah anda rutin mengonsumsi minuman beralkohol lebih dari 2 kali dalam seminggu?',
                'medical_explanation' => 'Alkohol meningkatkan aktivitas saraf simpatis yang memacu jantung. Konsumsi rutin jangka panjang merusak otot jantung.',
                'recommendation' => 'Batasi konsumsi alkohol. Pria maksimal 2 gelas/hari, wanita 1 gelas/hari. Sebaiknya hindari sama sekali.',
            ],
            [
                'code' => 'E10',
                'name' => 'Mudah marah atau ketidakstabilan emosional',
                'question_text' => 'Apakah anda termasuk orang yang mudah marah, tidak sabaran, atau sulit mengendalikan emosi?',
                'medical_explanation' => 'Sifat agresif/mudah marah dikaitkan dengan risiko hipertensi yang lebih tinggi pada usia muda karena reaktivitas kardiovaskular yang berlebihan.',
                'recommendation' => 'Latih manajemen amarah (Anger Management). Tarik napas dalam-dalam saat emosi terpancing.',
            ],
            [
                'code' => 'E11',
                'name' => 'Minuman Berenergi',
                'question_text' => 'Apakah anda sering mengonsumsi minuman berenergi (Kratingdaeng, Extra Joss, dll) lebih dari 3 kali dalam seminggu?',
                'medical_explanation' => 'Kandungan kafein dosis tinggi dan taurin memicu lonjakan detak jantung dan tekanan darah secara akut.',
                'recommendation' => 'Ganti minuman berenergi dengan air kelapa muda atau jus buah segar tanpa gula sebagai penambah energi alami.',
            ],
            [
                'code' => 'E12',
                'name' => 'Perokok pasif',
                'question_text' => 'Apakah anda sering terpapar asap rokok lebih dari 3 kali per minggu (tinggal/berada di lingkungan perokok aktif)?',
                'medical_explanation' => 'Asap rokok orang lain (secondhand smoke) mengandung racun yang sama berbahayanya dan tetap dapat merusak pembuluh darah Anda.',
                'recommendation' => 'Hindari ruangan penuh asap rokok. Tegur dengan sopan atau menyingkirlah saat ada orang merokok di dekat Anda.',
            ],
        ];

        foreach ($factors as $factor) {
            RiskFactor::create($factor);
        }
    }
}
