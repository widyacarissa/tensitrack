<?php

namespace Tests\Feature;

use App\Models\FaktorRisiko;
use App\Models\TingkatRisiko;
use App\Services\DiagnosisService;
use Database\Seeders\AuthGroupSeeder;
use Database\Seeders\AuthGroupUserSeeder;
use Database\Seeders\ComplexRuleSeeder;
use Database\Seeders\FaktorRisikoSeeder;
use Database\Seeders\TingkatRisikoSeeder;
use Database\Seeders\UserCustomSeeder;
use Database\Seeders\UserProfileSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiagnosisServiceTest extends TestCase
{
    use RefreshDatabase;

    private DiagnosisService $diagnosisService;
    private $h01, $h02, $h03; // Tingkat Risiko
    private $e01; // Faktor Risiko Klinis
    private $lifestyleFactors; // Gaya Hidup
    private $otherClinicalFactors; // Faktor Klinis Lainnya

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Run all necessary seeders to populate the database with rules and factors
        $this->seed(TingkatRisikoSeeder::class);
        $this->seed(FaktorRisikoSeeder::class);
        $this->seed(ComplexRuleSeeder::class);

        // Instantiate the service we are testing
        $this->diagnosisService = $this->app->make(DiagnosisService::class);

        // Fetch the models we'll need for our assertions
        $this->h01 = TingkatRisiko::where('kode', 'H01')->first();
        $this->h02 = TingkatRisiko::where('kode', 'H02')->first();
        $this->h03 = TingkatRisiko::where('kode', 'H03')->first();
        $this->e01 = FaktorRisiko::where('kode', 'E01')->first();

        $this->lifestyleFactors = FaktorRisiko::where('tipe', 'GAYA_HIDUP')->get();
        $this->otherClinicalFactors = FaktorRisiko::whereIn('kode', ['E02', 'E03'])->get();
    }

    // ========== High Risk (H03) Tests ==========

    public function test_returns_h03_with_e01_and_3_other_factors()
    {
        $factors = collect([$this->e01])
            ->merge($this->otherClinicalFactors) // E02, E03 (count: 2)
            ->merge($this->lifestyleFactors->take(1)); // (count: 1) -> total 3 other factors

        $result = $this->diagnosisService->calculateRisk($factors->pluck('id')->toArray());
        $this->assertNotNull($result);
        $this->assertEquals($this->h03->id, $result->id);
    }

    public function test_returns_h03_with_5_lifestyle_factors_and_no_e01()
    {
        $factors = $this->lifestyleFactors->take(5);

        $result = $this->diagnosisService->calculateRisk($factors->pluck('id')->toArray());
        $this->assertNotNull($result);
        $this->assertEquals($this->h03->id, $result->id);
    }

    // ========== Medium Risk (H02) Tests ==========

    public function test_returns_h02_with_e01_and_2_other_factors()
    {
        $factors = collect([$this->e01])
            ->merge($this->otherClinicalFactors->take(2)); // total 2 other factors

        $result = $this->diagnosisService->calculateRisk($factors->pluck('id')->toArray());
        $this->assertNotNull($result);
        $this->assertEquals($this->h02->id, $result->id);
    }

    public function test_returns_h02_without_e01_and_3_other_factors()
    {
        $factors = $this->otherClinicalFactors // count: 2
            ->merge($this->lifestyleFactors->take(1)); // count: 1 -> total 3 factors

        $result = $this->diagnosisService->calculateRisk($factors->pluck('id')->toArray());
        $this->assertNotNull($result);
        $this->assertEquals($this->h02->id, $result->id);
    }
    
    public function test_returns_h02_with_e01_and_no_other_factors()
    {
        $factors = collect([$this->e01]); // Just E01

        $result = $this->diagnosisService->calculateRisk($factors->pluck('id')->toArray());
        $this->assertNotNull($result);
        $this->assertEquals($this->h02->id, $result->id);
    }
    
    // ========== Low Risk (H01) Tests ==========

    public function test_returns_h01_without_e01_and_2_other_factors()
    {
        $factors = $this->otherClinicalFactors->take(2); // total 2 factors

        $result = $this->diagnosisService->calculateRisk($factors->pluck('id')->toArray());
        $this->assertNotNull($result);
        $this->assertEquals($this->h01->id, $result->id);
    }

    public function test_returns_h01_with_no_factors_at_all()
    {
        $result = $this->diagnosisService->calculateRisk([]);
        $this->assertNotNull($result);
        $this->assertEquals($this->h01->id, $result->id);
    }

    // ========= No Match Test ============
    
    public function test_rule_priority_h03_over_h02()
    {
        // This case matches H03 (>=5 lifestyle) and H02 (not E01 and >=3 others)
        // H03 should be chosen due to higher priority
        $factors = $this->lifestyleFactors->take(5);

        $result = $this->diagnosisService->calculateRisk($factors->pluck('id')->toArray());
        $this->assertNotNull($result);
        $this->assertEquals($this->h03->id, $result->id);
    }
}