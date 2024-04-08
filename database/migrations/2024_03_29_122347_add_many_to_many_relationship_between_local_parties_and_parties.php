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
        Schema::create('local_party_party', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('party_id')->constrained()->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('local_party_id')->constrained()->onUpdate('cascade')->onDelete('cascade');

            $table->unique(['party_id', 'local_party_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('local_party_party');
    }
};
