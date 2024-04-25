<?php

namespace App\Http\Controllers;

use App\Models\Response;

class StatisticsController extends Controller
{
    public function index()
    {
        return response()->json([
            'responses_last_24h' => Response::query()->since(now()->subDay())->count(),
        ]);
    }
}
