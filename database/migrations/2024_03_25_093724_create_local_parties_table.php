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
        Schema::create('local_parties', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name');
            $table->foreignId('country_id')->constrained()->onUpdate('CASCADE')->onDelete('RESTRICT');
            $table->foreignId('party_id')->constrained()->onUpdate('CASCADE')->onDelete('RESTRICT');

            $table->string('logo');
            $table->string('link', 512);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_parties');
    }
};
