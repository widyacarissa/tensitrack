<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Utama: Screenings
        Schema::create('screenings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('client_name'); // Snapshot nama
            $table->integer('snapshot_age');
            $table->decimal('snapshot_height', 5, 2);
            $table->decimal('snapshot_weight', 5, 2);
            $table->integer('snapshot_systolic');
            $table->integer('snapshot_diastolic');
            $table->string('result_level'); // e.g., "Risiko Tinggi"
            $table->decimal('score', 5, 2)->nullable(); // Probabilitas/Score
            $table->timestamps();
        });

        // Tabel Detail: Screening Details (Jawaban Faktor Risiko)
        Schema::create('screening_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('screening_id')->constrained('screenings')->onDelete('cascade');
            $table->foreignId('risk_factor_id')->constrained('risk_factors')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('screening_details');
        Schema::dropIfExists('screenings');
    }
};