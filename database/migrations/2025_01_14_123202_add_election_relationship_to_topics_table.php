<?php

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
        $electionId = DB::table('elections')->select('id')->orderBy('id', 'ASC')->first()->id;

        Schema::table('topics', function (Blueprint $table) use ($electionId) {
            $table->foreignId('election_id')->default($electionId)->constrained()->onUpdate('cascade')->onDelete('cascade');
        });

        // Reset default value
        Schema::table('topics', function (Blueprint $table) {
            $table->foreignId('election_id')->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            $table->dropColumn('election_id');
        });
    }
};
