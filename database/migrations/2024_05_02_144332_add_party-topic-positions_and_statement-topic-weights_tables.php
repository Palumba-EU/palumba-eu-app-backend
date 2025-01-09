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
        Schema::create('party_topic_positions', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('party_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('topic_id')->constrained()->onUpdate('cascade')->onDelete('cascade');

            $table->tinyInteger('position');

            $table->unique(['party_id', 'topic_id']);
        });

        Schema::create('statement_topic_weights', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('statement_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('topic_id')->constrained()->onUpdate('cascade')->onDelete('cascade');

            $table->tinyInteger('weight');

            $table->unique(['statement_id', 'topic_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('statement_topic_weights');
        Schema::dropIfExists('party_topic_positions');
    }
};
