<?php

namespace App\Http\Controllers;

use App\Http\Resources\PartyResource;
use App\Http\Resources\TopicResource;
use App\Models\Party;
use App\Models\Topic;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Http\JsonResponse;

class ResultsController extends Controller
{
    public function index(): JsonResponse
    {
        $topics = Topic::query()->with(['statements' => fn (BelongsToMany $query) => $query->published()])->published()->get();
        $parties = Party::query()->with([
            'local_parties' => fn (BelongsToMany $query) => $query->published(),
            'policies' => fn (HasMany $query) => $query->published(),
            'mood_images' => fn (HasMany $query) => $query->published(),
        ])->published()->get();

        return response()->json([
            'topics' => TopicResource::collection($topics),
            'parties' => PartyResource::collection($parties),
        ]);
    }
}
