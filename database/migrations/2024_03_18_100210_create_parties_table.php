<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string('name');
            $table->foreignId('country_id')->constrained()->onUpdate('CASCADE')->onDelete('RESTRICT');

            $table->string('color', 7); // hex code #aaddcc

            // Position of party in 5d space
            $table->smallInteger('p1')->default(0);
            $table->smallInteger('p2')->default(0);
            $table->smallInteger('p3')->default(0);
            $table->smallInteger('p4')->default(0);
            $table->smallInteger('p5')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parties');
    }
};
