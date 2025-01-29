<?php

namespace App\Http\Controllers;

use App\Http\Resources\StatementResource;
use App\Models\Election;
use App\Models\Statement;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StatementController extends Controller
{
    public function index(Request $request, string $language, Election $election): AnonymousResourceCollection
    {
        $query = Statement::query()
            ->election($election)
            ->published()
            ->with(['weights'])
            ->orderByDesc('is_tutorial')
            ->orderBy('sort_index')
            ->orderBy('statement');

        if (! $request->has('include_tutorial')) {
            $query = $query->withoutTutorial();
        }

        return StatementResource::collection($query->get());
    }
}
