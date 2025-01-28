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
        Schema::table('elections', function (Blueprint $table) {
            $table->string('egg_title')->default('');
            $table->text('egg_description')->default('');
            $table->string('egg_image')->default('');
            $table->string('egg_yes_btn_text')->default('');
            $table->string('egg_yes_btn_link')->default('');
            $table->string('egg_no_btn_text')->default('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropColumn(['egg_title', 'egg_description', 'egg_image', 'egg_yes_btn_text', 'egg_yes_btn_link', 'egg_no_btn_text']);
        });
    }
};
