<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('risk_levels', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // H01, H02
            $table->string('name');
            $table->text('description');
            $table->text('suggestion');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('risk_levels');
    }
};