<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('statements', function (Blueprint $table) {
            // Vector in 5D space
            $table->smallInteger('w1')->default(0);
            $table->smallInteger('w2')->default(0);
            $table->smallInteger('w3')->default(0);
            $table->smallInteger('w4')->default(0);
            $table->smallInteger('w5')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('statements', function (Blueprint $table) {
            $table->dropColumn(['w1', 'w2', 'w3', 'w4', 'w5']);
        });
    }
};
