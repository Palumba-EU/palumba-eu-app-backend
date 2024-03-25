<?php

namespace App\Http\Controllers;

use App\Http\Resources\SponsorResource;
use App\Models\Sponsor;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SponsorController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return SponsorResource::collection(Sponsor::query()->get());
    }
}
