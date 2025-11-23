<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rule', function (Blueprint $table) {
            $table->smallInteger('id', true, true);
            $table->unsignedSmallInteger('tingkat_risiko_id');
            $table->foreign('tingkat_risiko_id')->references('id')->on('tingkat_risiko');
            $table->unsignedSmallInteger('faktor_risiko_id');
            $table->foreign('faktor_risiko_id')->references('id')->on('faktor_risiko');
            $table->unsignedSmallInteger('next_first_faktor_risiko_id')->nullable();
            $table->foreign('next_first_faktor_risiko_id')->references('id')->on('faktor_risiko');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('rule');
    }
};
