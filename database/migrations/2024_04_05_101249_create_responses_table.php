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
        Schema::create('responses', function (Blueprint $table) {
            $table->id();

            $table->timestamp('created_at')->nullable();

            $table->integer('age')->nullable();
            $table->foreignId('country_id')->constrained()->onUpdate('cascade')->onDelete('restrict');
            $table->string('language_id');
            $table->string('gender')->nullable();
        });

        Schema::create('response_statement', function (Blueprint $table) {
            $table->id();

            $table->foreignId('response_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('statement_id')->constrained()->onUpdate('cascade')->onDelete('restrict');

            $table->tinyInteger('answer')->nullable();

            $table->unique(['response_id', 'statement_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('response_statement');
        Schema::dropIfExists('responses');
    }
};
