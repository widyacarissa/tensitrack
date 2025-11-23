<?php

namespace Database\Seeders;

use App\Models\FaktorRisiko;
use App\Models\Rule;
use App\Models\TingkatRisiko;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $mapping = [
            'Virus Kuning (Gemini Virus)' => [
                'sekitar tulang daun menebal berwarna hijau tua dan daun berwarna kuning',
                'tulang daun menebal dan daun menggulung ke atas',
                'daun mengecil dan berwarna kuning terang',
                'tanaman kerdil dan tidak berbuah',
            ],
            // Tambah tingkat risiko lain di sini bila perlu...
        ];

        DB::transaction(function () use ($mapping) {
            foreach ($mapping as $tingkatRisikoName => $faktorRisikoNames) {
                $tingkatRisiko = TingkatRisiko::where('name', $tingkatRisikoName)->first();
                if (! $tingkatRisiko) {
                    // Lewatkan jika belum ada (pastikan urutan seeding benar)
                    continue;
                }

                $faktorRisikos = FaktorRisiko::whereIn('name', $faktorRisikoNames)->get(['id', 'name']);

                foreach ($faktorRisikos as $fr) {
                    Rule::firstOrCreate([
                        'tingkat_risiko_id' => $tingkatRisiko->id,
                        'faktor_risiko_id' => $fr->id,
                    ]);
                }
            }
        });
    }
}
