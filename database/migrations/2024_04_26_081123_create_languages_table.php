<?php

use App\Models\Language;
use App\Services\CrowdInTranslation;
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
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->boolean('published');
            $table->string('name');
            $table->string('code')->unique();
        });

        Language::create([
            'code' => 'en',
            'name' => 'English',
            'published' => true,
        ]);

        /** @var CrowdInTranslation $crowdIn */
        $crowdIn = resolve(CrowdInTranslation::class);
        $languages = $crowdIn->listTargetLanguages();
        foreach ($languages as $language) {
            Language::create([
                'code' => $language['language_code'],
                'name' => $language['name'],
                'published' => false,
            ]);
        }

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
