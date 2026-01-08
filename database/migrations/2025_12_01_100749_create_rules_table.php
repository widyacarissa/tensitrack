<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rules', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // R1, R2
            $table->foreignId('risk_level_id')->constrained('risk_levels')->onDelete('cascade');
            $table->string('operator')->default('AND'); // 'AND' atau 'OR'
            $table->integer('min_other_factors')->default(0);
            $table->integer('max_other_factors')->default(99);
            $table->integer('priority')->default(0);
            $table->timestamps();
        });

        // Tabel Pivot: rule_risk_factors (Many-to-Many)
        Schema::create('rule_risk_factors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rule_id')->constrained('rules')->onDelete('cascade');
            $table->foreignId('risk_factor_id')->constrained('risk_factors')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rule_risk_factors');
        Schema::dropIfExists('rules');
    }
};
