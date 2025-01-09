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
        Schema::create('mood_images', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('party_id')->constrained()->onUpdate('CASCADE')->onDelete('CASCADE');
            $table->string('image');

            $table->string('link_text')->nullable()->default(null);
            $table->string('link', 512)->nullable()->default(null);
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mood_images');
    }
};
