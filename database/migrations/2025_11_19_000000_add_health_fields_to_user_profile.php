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
        Schema::table('user_profile', function (Blueprint $table) {
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('user_id');
            $table->integer('age')->nullable()->after('gender');
            $table->decimal('weight', 5, 2)->nullable()->after('age')->comment('Weight in kg');
            $table->decimal('height', 5, 2)->nullable()->after('weight')->comment('Height in cm');
            $table->decimal('bmi', 5, 2)->nullable()->after('height')->comment('Body Mass Index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_profile', function (Blueprint $table) {
            $table->dropColumn(['gender', 'age', 'weight', 'height', 'bmi']);
        });
    }
};
