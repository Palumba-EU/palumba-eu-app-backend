<?php

use App\Models\LocalParty;
use App\Models\Party;
use App\Models\Policy;
use App\Models\Sponsor;
use App\Models\Statement;
use App\Models\Topic;

return [
    'token' => env('CROWDIN_TOKEN'),
    'project_id' => env('CROWDIN_PROJECT_ID'),

    'storage' => [
        'disk' => env('TRANSLATABLE_SOURCE_DISK', 'local'),
        'path' => env('TRANSLATABLE_BASE_PATH', 'sources'),
    ],

    'translatable_models' => [
        LocalParty::class,
        Party::class,
        Policy::class,
        Sponsor::class,
        Statement::class,
        Topic::class,
    ],

];
