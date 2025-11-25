<?php

namespace Database\Seeders;

use App\Models\FaktorRisiko;
use App\Models\Rule;
use App\Models\RuleCondition;
use App\Models\RuleConditionGroup;
use App\Models\TingkatRisiko;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ComplexRuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Disabling foreign key checks and truncating rule tables...');
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        RuleCondition::truncate();
        RuleConditionGroup::truncate();
        Rule::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        $this->command->info('Rule tables truncated and foreign key checks re-enabled.');

        // 2. Define factor types
        $this->command->info('Updating risk factor types...');
        FaktorRisiko::whereIn('kode', ['E01', 'E02', 'E03'])->update(['tipe' => 'KLINIS']);
        FaktorRisiko::whereIn('kode', ['E04', 'E05', 'E06', 'E07', 'E08', 'E09', 'E10', 'E11'])->update(['tipe' => 'GAYA_HIDUP']);
        
        // 3. Get required models
        $this->command->info('Fetching required data...');
        $e01 = FaktorRisiko::where('kode', 'E01')->firstOrFail();
        $h01 = TingkatRisiko::where('kode', 'H01')->firstOrFail(); // Rendah
        $h02 = TingkatRisiko::where('kode', 'H02')->firstOrFail(); // Sedang
        $h03 = TingkatRisiko::where('kode', 'H03')->firstOrFail(); // Tinggi

        // 4. Seed Rule H03 (High Risk) - Priority 30
        $this->command->info('Seeding High Risk Rule (H03)...');
        $ruleH03 = Rule::create([
            'name' => 'Tingkat Risiko Tinggi',
            'description' => 'Pasien memiliki faktor klinis E01 dan 3 atau lebih faktor risiko lain, ATAU memiliki 5 atau lebih faktor risiko gaya hidup.',
            'tingkat_risiko_id' => $h03->id,
            'priority' => 30,
        ]);

        // Group 1: (E01 AND >=3 other factors)
        $groupH03_1 = $ruleH03->conditionGroups()->create();
        $groupH03_1->conditions()->create([
            'type' => 'HAS_FAKTOR',
            'faktor_risiko_id' => $e01->id,
            'operator' => '==',
            'value' => 1, // Must have E01
        ]);
                    $groupH03_1->conditions()->create([
                        'type' => 'FAKTOR_LAIN_COUNT',
                        'operator' => '>=',
                        'value' => 3, // 3 or more other factors
                    ]);
        
                    // Group 2: (>=5 lifestyle factors)
                    $groupH03_2 = $ruleH03->conditionGroups()->create();
                    $groupH03_2->conditions()->create([
                        'type' => 'GAYA_HIDUP_COUNT',
                        'operator' => '>=',
                        'value' => 5,
                    ]);
        
                    // 5. Seed Rule H02 (Medium Risk) - Priority 20
                    $this->command->info('Seeding Medium Risk Rule (H02)...');
                    $ruleH02 = Rule::create([
                        'name' => 'Tingkat Risiko Sedang',
                        'description' => 'Pasien memiliki E01 dan 0-2 faktor lain, ATAU tidak memiliki E01 tapi punya 3 atau lebih faktor lain.',
                        'tingkat_risiko_id' => $h02->id,
                        'priority' => 20,
                    ]);
        
                    // Group 1: (E01 AND 0-2 other factors)
                    $groupH02_1 = $ruleH02->conditionGroups()->create();
                    $groupH02_1->conditions()->create([
                        'type' => 'HAS_FAKTOR',
                        'faktor_risiko_id' => $e01->id,
                        'operator' => '==',
                        'value' => 1, // Must have E01
                    ]);
                    $groupH02_1->conditions()->create([
                        'type' => 'FAKTOR_LAIN_COUNT',
                        'operator' => '<=',
                        'value' => 2,
                    ]);
        
                    // Group 2: (NOT E01 AND >=3 other factors)
                    $groupH02_2 = $ruleH02->conditionGroups()->create();
                    $groupH02_2->conditions()->create([
                        'type' => 'HAS_FAKTOR',
                        'faktor_risiko_id' => $e01->id,
                        'operator' => '==',
                        'value' => 0, // Must NOT have E01
                    ]);
                    $groupH02_2->conditions()->create([
                        'type' => 'FAKTOR_TOTAL_COUNT',
                        'operator' => '>=',
                        'value' => 3,
                    ]);
        
                    // 6. Seed Rule H01 (Low Risk) - Priority 10
                    $this->command->info('Seeding Low Risk Rule (H01)...');
                    $ruleH01 = Rule::create([
                        'name' => 'Tingkat Risiko Rendah',
                        'description' => 'Pasien tidak memiliki E01 dan punya 2 atau kurang faktor risiko lain.',
                        'tingkat_risiko_id' => $h01->id,
                        'priority' => 10,
                    ]);
        
                    // Group 1: (NOT E01 AND <=2 other factors)
                    $groupH01_1 = $ruleH01->conditionGroups()->create();
                    $groupH01_1->conditions()->create([
                        'type' => 'HAS_FAKTOR',
                        'faktor_risiko_id' => $e01->id,
                        'operator' => '==',
                        'value' => 0, // Must NOT have E01
                    ]);
                    $groupH01_1->conditions()->create([
                        'type' => 'FAKTOR_TOTAL_COUNT',
                        'operator' => '<=',
                        'value' => 2,
                    ]);
        $this->command->info('Complex rule engine seeded successfully!');
    }
}