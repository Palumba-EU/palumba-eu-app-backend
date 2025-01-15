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
        Schema::create('election_sponsor', function (Blueprint $table) {
            $table->foreignId('election_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('sponsor_id')->constrained()->onUpdate('cascade')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('election_sponsor');
    }
};
