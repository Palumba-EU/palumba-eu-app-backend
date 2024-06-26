<?php

use App\Models\Country;
use App\Models\LocalParty;
use App\Models\MoodImage;
use App\Models\Party;
use App\Models\Policy;
use App\Models\Sponsor;
use App\Models\Statement;
use App\Models\Topic;

return [
    'token' => env('CROWDIN_TOKEN'),
    'project_id' => env('CROWDIN_PROJECT_ID'),

    'target_language_cache_time' => env('CROWDIN_TARGET_LANGUAGE_CACHE_TIME', 3600),

    'storage' => [
        'disk' => env('TRANSLATABLE_SOURCE_DISK', 'public'),
        'path' => env('TRANSLATABLE_BASE_PATH', 'sources'),
    ],

    'translatable_models' => [
        Country::class,
        LocalParty::class,
        Party::class,
        Policy::class,
        MoodImage::class,
        Sponsor::class,
        Statement::class,
        Topic::class,
    ],

];
