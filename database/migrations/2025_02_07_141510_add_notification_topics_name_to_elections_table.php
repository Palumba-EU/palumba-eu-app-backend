<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->string('notification_topic')->default('');
        });

        DB::table('elections')->select(['id', 'name'])->get()->each(function ($row) {
            DB::table('elections')->where('id', $row->id)->update([
                'notification_topic' => Str::slug($row->name),
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('elections', function (Blueprint $table) {
            $table->dropColumn('notification_topic');
        });
    }
};
