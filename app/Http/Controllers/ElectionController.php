<?php

namespace App\Http\Controllers;

use App\Http\Resources\ElectionResource;
use App\Models\Election;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ElectionController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ElectionResource::collection(Election::query()->with(['country'])->published()->get());
    }
}
