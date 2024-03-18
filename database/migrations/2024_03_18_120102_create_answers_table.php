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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('statement_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->morphs('answerable');

            $table->tinyInteger('answer');

            $table->unique(['statement_id', 'answerable_id', 'answerable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
