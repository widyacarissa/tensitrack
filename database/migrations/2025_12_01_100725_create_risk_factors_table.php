<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risk_factors', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // E01, E02
            $table->string('name');
            $table->text('question_text')->nullable();
            $table->string('category')->nullable()->default('LIFESTYLE'); // MEDIS, LIFESTYLE, DIET, PSIKOLOGIS
            $table->text('medical_explanation')->nullable(); // Penjelasan medis kenapa ini bahaya
            $table->text('recommendation')->nullable(); // Saran spesifik jika user memilih ini
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_factors');
    }
};