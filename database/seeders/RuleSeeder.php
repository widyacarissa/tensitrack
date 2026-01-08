<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Rule;
use App\Models\RiskFactor;
use App\Models\RiskLevel;
use Illuminate\Support\Facades\Schema;

class RuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        Rule::truncate(); 
        \DB::table('rule_risk_factors')->truncate();
        Schema::enableForeignKeyConstraints();

        // Ambil Faktor Spesial
        $e01 = RiskFactor::where('code', 'E01')->first(); // Tensi
        $e02 = RiskFactor::where('code', 'E02')->first(); // Keluarga

        // Ambil Level Risiko
        $h_tinggi = RiskLevel::where('code', 'H04')->first(); 
        $h_sedang = RiskLevel::where('code', 'H03')->first(); 
        $h_rendah = RiskLevel::where('code', 'H02')->first(); 
        $h_sehat  = RiskLevel::where('code', 'H01')->first(); 

        if (!$h_tinggi || !$h_sedang || !$h_rendah || !$h_sehat) {
            return;
        }

        // --- KELOMPOK RISIKO TINGGI (H04) ---

        // PRIO 1: Jika ada E01 DAN E02 dan 1-10 faktor lain
        $r1 = Rule::create([
            'code' => 'R1',
            'risk_level_id' => $h_tinggi->id,
            'operator' => 'AND',
            'min_other_factors' => 1,
            'max_other_factors' => 10,
            'priority' => 1,
        ]);
        if($e01) $r1->riskFactors()->attach($e01->id);
        if($e02) $r1->riskFactors()->attach($e02->id);

        // PRIO 2: Jika punya SALAH SATU dari E01/E02 dan >7 faktor lain
        $r2 = Rule::create([
            'code' => 'R2',
            'risk_level_id' => $h_tinggi->id,
            'operator' => 'OR',
            'min_other_factors' => 8,
            'max_other_factors' => 99,
            'priority' => 2,
        ]);
        if($e01) $r2->riskFactors()->attach($e01->id);
        if($e02) $r2->riskFactors()->attach($e02->id);


        // --- KELOMPOK RISIKO SEDANG (H03) ---

        // PRIO 3: Jika punya SALAH SATU dari E01/E02 dan >= 5 faktor lain
        $r3 = Rule::create([
            'code' => 'R3',
            'risk_level_id' => $h_sedang->id,
            'operator' => 'OR',
            'min_other_factors' => 5,
            'max_other_factors' => 7,
            'priority' => 3,
        ]);
        if($e01) $r3->riskFactors()->attach($e01->id);
        if($e02) $r3->riskFactors()->attach($e02->id);

        // PRIO 4: Jika ga punya E01 dan E02, harus punya >= 7 faktor lain
        $r4 = Rule::create([
            'code' => 'R4',
            'risk_level_id' => $h_sedang->id,
            'operator' => 'AND', 
            'min_other_factors' => 7,
            'max_other_factors' => 99,
            'priority' => 4,
        ]);


        // --- KELOMPOK RISIKO RENDAH (H02) ---

        // PRIO 5: Jika punya SALAH SATU E01/E02 dan punya 1-4 faktor lain
        $r5 = Rule::create([
            'code' => 'R5',
            'risk_level_id' => $h_rendah->id,
            'operator' => 'OR',
            'min_other_factors' => 1,
            'max_other_factors' => 4,
            'priority' => 5,
        ]);
        if($e01) $r5->riskFactors()->attach($e01->id);
        if($e02) $r5->riskFactors()->attach($e02->id);


        // PRIO 6: Jika ga punya E01 dan E02, harus punya 4-6 faktor lain
        $r6 = Rule::create([
            'code' => 'R6',
            'risk_level_id' => $h_rendah->id,
            'operator' => 'AND',
            'min_other_factors' => 4,
            'max_other_factors' => 6,
            'priority' => 6,
        ]);


        // --- TIDAK BERISIKO (H01) ---
        
        // PRIO 7: Ga punya E01 dan E02, tapi faktor risiko lain 0-3
        $r7 = Rule::create([
            'code' => 'R7',
            'risk_level_id' => $h_sehat->id,
            'operator' => 'AND',
            'min_other_factors' => 0,
            'max_other_factors' => 3,
            'priority' => 7,
        ]);
    }
}