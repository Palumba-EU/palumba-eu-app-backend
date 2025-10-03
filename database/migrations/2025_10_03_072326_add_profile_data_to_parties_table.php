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
        Schema::table('parties', function (Blueprint $table) {
            $table->text('profile_bio')->default('');
            $table->text('profile_affiliation')->default('');
            $table->text('profile_red_flags')->default('');

            $table->string('profile_link1', 512)->nullable()->default(null);
            $table->string('profile_link1_text')->nullable()->default(null);

            $table->string('profile_link2', 512)->nullable()->default(null);
            $table->string('profile_link2_text')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('parties', function (Blueprint $table) {
            $table->dropColumn([
                'profile_bio', 'profile_affiliation', 'profile_red_flags',
                'profile_link1', 'profile_link1_text',  'profile_link2', 'profile_link2_text',
            ]);
        });
    }
};
