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
        Schema::create('topics', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name')->unique();
            $table->string('color', 7);
            $table->string('icon');
        });

        Schema::create('statement_topic', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('statement_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->foreignId('topic_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('topics');
    }
};
