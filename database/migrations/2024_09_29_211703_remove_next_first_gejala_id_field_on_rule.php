<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rule', function (Blueprint $table) {
            $table->dropForeign(['next_first_faktor_risiko_id']);
            $table->dropColumn('next_first_faktor_risiko_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rule', function (Blueprint $table) {
            $table->unsignedSmallInteger('next_first_faktor_risiko_id')->nullable();
            $table->foreign('next_first_faktor_risiko_id')->references('id')->on('faktor_risiko');
        });
    }
};
