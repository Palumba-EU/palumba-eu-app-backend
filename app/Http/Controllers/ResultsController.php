<?php

namespace App\Http\Controllers;

use App\Http\Resources\PartyResource;
use App\Http\Resources\TopicResource;
use App\Models\Party;
use App\Models\Topic;
use Illuminate\Http\JsonResponse;

class ResultsController extends Controller
{
    public function index(): JsonResponse
    {
        $topics = Topic::query()->with('statements')->get();
        $parties = Party::query()->with(['local_parties', 'policies'])->get();

        return response()->json([
            'topics' => TopicResource::collection($topics),
            'parties' => PartyResource::collection($parties),
        ]);
    }
}
