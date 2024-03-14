<?php

namespace App\Http\Controllers;

use App\Http\Resources\StatementResource;
use App\Models\Statement;

class StatementController extends Controller
{
    public function index()
    {
        return StatementResource::collection(Statement::query()->orderBy('sort_index')->get());
    }
}
