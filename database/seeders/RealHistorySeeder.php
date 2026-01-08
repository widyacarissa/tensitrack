<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\User;
use App\Models\UserProfile;
use App\Models\RiskFactor;
use App\Models\Screening;
use App\Models\ScreeningDetail;
use App\Models\RiskLevel;
use App\Models\Rule;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RealHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file = base_path('riwayat-skrining-2025-12-19_21-18.xlsx');

        if (!file_exists($file)) {
            $this->command->error("File Excel tidak ditemukan: $file");
            return;
        }

        $this->command->info("Mengimpor & Menghitung Ulang Data Skrining (Turbo Mode)...");

        // Load Rules & Risk Levels (Cache in Memory)
        $rules = Rule::with('riskFactors')->orderBy('priority', 'ASC')->get();
        $defaultRisk = RiskLevel::where('code', 'H01')->first();
        $riskFactorMap = RiskFactor::all()->pluck('id', 'name')->toArray();

        // Referensi ID Faktor Spesial
        $e01 = RiskFactor::where('code', 'E01')->first(); // Tensi
        $e02 = RiskFactor::where('code', 'E02')->first(); // Keluarga
        $e08 = RiskFactor::where('code', 'E08')->first(); // Obesitas (BMI)

        $specialFactorIds = [];
        if ($e01) $specialFactorIds[] = $e01->id;
        if ($e02) $specialFactorIds[] = $e02->id;

        try {
            // 1. Baca Excel dengan Mode ReadDataOnly (Lebih Cepat)
            $reader = IOFactory::createReaderForFile($file);
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file);
            $data = $spreadsheet->getActiveSheet()->toArray();
            $headers = array_shift($data); // Skip header

            DB::beginTransaction();

            // 2. Pre-load Existing Users & Profiles
            $userCache = User::pluck('id', 'email')->toArray();
            
            $screeningDetailsBatch = [];
            $batchSize = 1000; // Tingkatkan batch size
            
            // Cache untuk menghindari duplikat screening dari file Excel yang sama (User + Tanggal)
            $processedScreenings = []; 

            foreach ($data as $index => $row) {
                if (empty($row[2])) continue;

                $tanggalStr = $row[1];
                $nama = $row[2];
                $gender = $row[3] == 'Laki-laki' ? 'L' : 'P';
                $usia = (int) $row[4];
                $tinggi = (float) $row[5];
                $berat = (float) $row[6];
                $tensi = $row[8];
                $faktorTerpilihText = $row[9];

                // Parse Tensi
                $systolic = 0; $diastolic = 0;
                if (str_contains($tensi, '/')) {
                    list($systolic, $diastolic) = explode('/', $tensi);
                }

                // Email Logic
                $emailFromExcel = isset($row[10]) ? trim($row[10]) : null;
                $email = !empty($emailFromExcel) ? $emailFromExcel : (Str::slug($nama, '_') . '@tensitrack.com');

                // 3. User Handling (In-Memory Check)
                if (isset($userCache[$email])) {
                    $userId = $userCache[$email];
                } else {
                    $newUser = User::create([
                        'name' => $nama,
                        'email' => $email,
                        'password' => Hash::make('password'),
                    ]);
                    $userId = $newUser->id;
                    $userCache[$email] = $userId;
                    
                    // Buat Profile hanya jika user baru (Asumsi user lama sudah punya profile dari UserSeeder atau run sebelumnya)
                    // Jika user lama perlu diupdate datanya, gunakan updateOrCreate di luar blok else ini.
                    // Untuk kecepatan, kita asumsi update profile dilakukan setiap saat untuk data terbaru.
                }

                // Update/Create Profile (Tetap jalankan untuk memastikan data fisik terupdate)
                // Kita gunakan updateOrCreate, tapi karena user ID sudah pasti, ini cukup cepat.
                UserProfile::updateOrCreate(
                    ['user_id' => $userId],
                    [
                        'full_name' => $nama, 'gender' => $gender, 'age' => $usia,
                        'height' => $tinggi, 'weight' => $berat,
                        'systolic' => (int) $systolic, 'diastolic' => (int) $diastolic,
                    ]
                );

                // 4. Parsing Faktor Risiko
                $selectedFactorIds = [];
                // a. Dari Text
                $faktorList = explode("\n", $faktorTerpilihText);
                foreach ($faktorList as $fRaw) {
                    $fName = trim(preg_replace('/^\d+\.\s*/', '', $fRaw));
                    if (empty($fName)) continue;
                    
                    // Optimasi pencarian string
                    foreach ($riskFactorMap as $nameInDb => $id) {
                        // Cek sederhana dulu sebelum replace yang mahal
                        if (stripos($nameInDb, $fName) !== false || stripos($fName, $nameInDb) !== false) {
                            $selectedFactorIds[] = $id;
                            break;
                        }
                        // Fallback ke replace jika perlu (opsional, bisa di-skip jika data bersih)
                        $cleanDbName = str_replace(['–', '-'], '-', $nameInDb);
                        $cleanFName = str_replace(['–', '-'], '-', $fName);
                        if (stripos($cleanDbName, $cleanFName) !== false || stripos($cleanFName, $cleanDbName) !== false) {
                            $selectedFactorIds[] = $id;
                            break;
                        }
                    }
                }

                // b. Logika Otomatis
                if ($e01 && (($systolic >= 121) || ($diastolic >= 81))) $selectedFactorIds[] = $e01->id;
                if ($e08 && $tinggi > 0 && $berat > 0) {
                    $h_m = $tinggi / 100;
                    $bmi = $berat / ($h_m * $h_m);
                    if ($bmi >= 25) $selectedFactorIds[] = $e08->id;
                }

                $finalFactors = array_unique($selectedFactorIds);

                // 5. Engine Diagnosa
                $diagnosedLevelName = ($defaultRisk) ? $defaultRisk->name : 'Tidak diketahui';
                
                // Pre-calculation for rules
                $others = array_diff($finalFactors, $specialFactorIds);
                $othersCount = count($others);

                foreach ($rules as $rule) {
                    $requiredIds = $rule->riskFactors->pluck('id')->toArray();
                    
                    // Cek Required
                    if ($rule->operator === 'OR' && !empty($requiredIds)) {
                        if (empty(array_intersect($requiredIds, $finalFactors))) continue;
                    } elseif (!empty($requiredIds)) {
                        if (!empty(array_diff($requiredIds, $finalFactors))) continue;
                    }

                    // Cek Jumlah Faktor Lain
                    if ($othersCount >= $rule->min_other_factors && $othersCount <= $rule->max_other_factors) {
                        $diagnosedLevelName = $rule->riskLevel->name;
                        break; 
                    }
                }

                // 6. Simpan Screening
                $createdAt = null;
                try { $createdAt = Carbon::createFromFormat('d/m/Y H:i', $tanggalStr); } catch (\Exception $e) { $createdAt = now(); }
                $createdAtStr = $createdAt->toDateTimeString();

                // Cek Duplikat di Memori (jika ada baris ganda di Excel)
                $uniqueKey = $userId . '|' . $createdAtStr;
                if (isset($processedScreenings[$uniqueKey])) continue;
                $processedScreenings[$uniqueKey] = true;

                // Insert Screening
                $screeningId = Screening::insertGetId([
                    'user_id' => $userId,
                    'client_name' => $nama,
                    'snapshot_age' => $usia,
                    'snapshot_height' => $tinggi,
                    'snapshot_weight' => $berat,
                    'snapshot_systolic' => (int) $systolic,
                    'snapshot_diastolic' => (int) $diastolic,
                    'result_level' => $diagnosedLevelName,
                    'score' => count($finalFactors),
                    'created_at' => $createdAt,
                    'updated_at' => now(),
                ]);

                // Prepare Detail Batch
                foreach ($finalFactors as $fid) {
                    $screeningDetailsBatch[] = [
                        'screening_id' => $screeningId,
                        'risk_factor_id' => $fid,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }

                // Bulk Insert Detail
                if (count($screeningDetailsBatch) >= $batchSize) {
                    ScreeningDetail::insert($screeningDetailsBatch);
                    $screeningDetailsBatch = [];
                }

                // Progress Bar Ringan
                if (($index + 1) % 250 == 0) {
                    $this->command->info("Memproses baris ke-" . ($index + 1));
                }
            }

            // Insert Sisa Detail
            if (!empty($screeningDetailsBatch)) {
                ScreeningDetail::insert($screeningDetailsBatch);
            }

            DB::commit();
            $this->command->info("Selesai! Data berhasil diimpor.");

        } catch (\Exception $e) {
            DB::rollBack();
            $this->command->error("Error: " . $e->getMessage());
        }
    }
}
