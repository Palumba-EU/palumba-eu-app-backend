<?php

namespace App\Http\Controllers;

use App\Http\Resources\PartyResource;
use App\Http\Resources\TopicResource;
use App\Models\Election;
use App\Models\Party;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;

class ResultsController extends Controller
{
    public function index(string $language, Election $election): JsonResponse
    {
        $topics = Topic::query()->election($election)->with([
            'statements' => fn (BelongsToMany $query) => $query->published(),
        ])->published()->get();

        $parties = Party::query()->election($election)->with([
            'local_parties' => fn (BelongsToMany $query) => $query->published(),
            'policies' => fn (HasMany $query) => $query->published(),
            'mood_images' => fn (HasMany $query) => $query->published(),
            'statements' => fn (BelongsToMany $query) => $query->published()->election($election),
            'positions' => fn (BelongsToMany $query) => $query->published()->election($election),
            'unavailable_in_countries' => fn (BelongsToMany $query) => $query->published()->parent($election->country_id),
        ])->published()->get();

        return response()->json([
            'topics' => TopicResource::collection($topics),
            'parties' => PartyResource::collection($parties),
        ]);
    }
}
