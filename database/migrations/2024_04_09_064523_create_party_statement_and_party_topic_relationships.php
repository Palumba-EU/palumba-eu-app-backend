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
        // In the beta phase, the result will be calculated with parties example answers
        Schema::create('party_statement', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('party_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('statement_id')->constrained()->onUpdate('cascade')->onDelete('cascade');

            $table->tinyInteger('answer');

            $table->unique(['party_id', 'statement_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('party_statement');
    }
};
