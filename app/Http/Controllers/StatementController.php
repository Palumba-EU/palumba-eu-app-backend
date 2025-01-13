<?php

namespace App\Http\Controllers;

use App\Http\Resources\StatementResource;
use App\Models\Election;
use App\Models\Statement;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StatementController extends Controller
{
    public function index(string $language, Election $election): AnonymousResourceCollection
    {
        return StatementResource::collection(
            Statement::query()
                ->election($election)
                ->published()
                ->with(['weights'])
                ->orderBy('sort_index')
                ->orderBy('statement')
                ->get()
        );
    }
}
