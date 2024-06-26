<?php

namespace App\Http\Controllers;

use App\Http\Resources\StatementResource;
use App\Models\Statement;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StatementController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return StatementResource::collection(Statement::query()->published()->with(['weights'])->orderBy('sort_index')->orderBy('statement')->get());
    }
}
