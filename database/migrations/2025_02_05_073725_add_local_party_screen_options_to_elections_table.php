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
        Schema::table('elections', function (Blueprint $table) {
            $table->string('lp_logo')->nullable()->default(null);
            $table->string('lp_text')->nullable()->default(null);
            $table->string('lp_link')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropColumn(['lp_logo', 'lp_text', 'lp_link']);
        });
    }
};
