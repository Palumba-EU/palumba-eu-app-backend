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
        Schema::table('parties', function (Blueprint $table) {
            $table->dropColumn(['p1', 'p2', 'p3', 'p4', 'p5']);
        });

        Schema::table('statements', function (Blueprint $table) {
            $table->dropColumn(['w1', 'w2', 'w3', 'w4', 'w5']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('statements', function (Blueprint $table) {
            // Vector in 5D space
            $table->smallInteger('w1')->default(0);
            $table->smallInteger('w2')->default(0);
            $table->smallInteger('w3')->default(0);
            $table->smallInteger('w4')->default(0);
            $table->smallInteger('w5')->default(0);
        });

        Schema::table('parties', function (Blueprint $table) {
            // Position of party in 5d space
            $table->smallInteger('p1')->default(0);
            $table->smallInteger('p2')->default(0);
            $table->smallInteger('p3')->default(0);
            $table->smallInteger('p4')->default(0);
            $table->smallInteger('p5')->default(0);
        });
    }
};
