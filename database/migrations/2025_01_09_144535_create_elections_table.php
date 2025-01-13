<?php

use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->boolean('published');
            $table->timestamp('date');
            $table->string('name');

            $table->foreignId('country_id')->nullable()->constrained()->onUpdate('CASCADE')->onDelete('RESTRICT');
        });

        DB::table('elections')->insert([
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'published' => true,
            'date' => Carbon::createFromDate(2024, 6, 6),
            'name' => 'European elections 2024',
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('elections');
    }
};
