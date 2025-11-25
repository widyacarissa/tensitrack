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
        // 1. Drop the old, simple 'rule' table.
        Schema::dropIfExists('rule');

        // 2. Add a 'type' column to 'faktor_risiko' to distinguish between different kinds of factors.
        Schema::table('faktor_risiko', function (Blueprint $table) {
            $table->string('tipe', 50)->after('name')->default('UMUM'); // e.g., UMUM, GAYA_HIDUP, KLINIS
        });

        // 3. Create the new 'rules' table to define a complete rule.
        Schema::create('rules', function (Blueprint $table) {
            $table->smallInteger('id', true, true);
            $table->string('name')->unique();
            $table->text('description')->nullable();
            $table->unsignedSmallInteger('tingkat_risiko_id');
            $table->foreign('tingkat_risiko_id')->references('id')->on('tingkat_risiko')->onDelete('cascade');
            $table->integer('priority')->default(0)->comment('Higher priority rules are evaluated first.');
            $table->timestamps();
        });

        // 4. Create 'rule_condition_groups' to allow for (A AND B) OR (C) logic.
        // Each rule can have one or more groups. If any group evaluates to true, the rule passes.
        Schema::create('rule_condition_groups', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('rule_id');
            $table->foreign('rule_id')->references('id')->on('rules')->onDelete('cascade');
            $table->timestamps();
        });

        // 5. Create 'rule_conditions' which are the specific conditions within a group.
        // All conditions in a group must be true for the group to pass (AND logic).
        Schema::create('rule_conditions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('rule_condition_group_id');
            $table->foreign('rule_condition_group_id')->references('id')->on('rule_condition_groups')->onDelete('cascade');
            
            $table->string('type')->comment('e.g., HAS_FAKTOR, FAKTOR_COUNT, GAYA_HIDUP_COUNT');
            
            $table->unsignedSmallInteger('faktor_risiko_id')->nullable()->comment('Used for HAS_FAKTOR type');
            $table->foreign('faktor_risiko_id')->references('id')->on('faktor_risiko')->onDelete('cascade');
            
            $table->string('operator', 10)->comment('e.g., >=, <=, ==');
            $table->integer('value')->comment('The value to compare against, e.g., the count of factors.');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop tables in the reverse order of creation
        Schema::dropIfExists('rule_conditions');
        Schema::dropIfExists('rule_condition_groups');
        Schema::dropIfExists('rules');

        // Remove the 'type' column from 'faktor_risiko'
        Schema::table('faktor_risiko', function (Blueprint $table) {
            $table->dropColumn('tipe');
        });

        // Re-create the old 'rule' table to restore schema state
        Schema::create('rule', function (Blueprint $table) {
            $table->smallInteger('id', true, true);
            $table->unsignedSmallInteger('tingkat_risiko_id');
            $table->foreign('tingkat_risiko_id')->references('id')->on('tingkat_risiko');
            $table->unsignedSmallInteger('faktor_risiko_id');
            $table->foreign('faktor_risiko_id')->references('id')->on('faktor_risiko');
            $table->timestamps();
        });
    }
};