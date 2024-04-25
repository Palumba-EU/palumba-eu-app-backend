<?php

use App\Models\Response;
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
        Schema::table('responses', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique();
        });

        Schema::table('response_statement', function (Blueprint $table) {
            $table->foreignUuid('response_uuid')->nullable()->constrained('responses', 'uuid')->onUpdate('cascade')->onDelete('restrict');
        });

        Response::query()->each(function (Response $response) use (&$map) {
            $response->uuid = Str::uuid()->toString();
            $response->save();

            DB::update(
                'UPDATE response_statement SET response_uuid = ? WHERE response_id = ?',
                [$response->uuid, $response->id]
            );
        });

        Schema::table('response_statement', function (Blueprint $table) {
            $table->dropColumn('response_id');
            $table->foreignUuid('response_uuid')->nullable(false)->change();
        });

        Schema::table('responses', function (Blueprint $table) {
            $table->dropColumn('id');
            $table->uuid('uuid')->nullable(false)->primary()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // We explicitly do not want to be able to roll back this change, due to privacy concerns of the previous version
    }
};
