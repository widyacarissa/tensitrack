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
        Schema::table('tingkat_risiko', function (Blueprint $table) {
            $table->renameColumn('name', 'tingkat_risiko');
            $table->renameColumn('reason', 'keterangan');
            $table->renameColumn('solution', 'saran');
            $table->dropColumn('image');
            $table->string('kode')->after('id')->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tingkat_risiko', function (Blueprint $table) {
            $table->renameColumn('tingkat_risiko', 'name');
            $table->renameColumn('keterangan', 'reason');
            $table->renameColumn('saran', 'solution');
            $table->string('image', 255)->nullable()->after('solution');
            $table->dropColumn('kode');
        });
    }
};
