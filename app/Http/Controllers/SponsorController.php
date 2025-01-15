<?php

namespace App\Http\Controllers;

use App\Http\Resources\SponsorResource;
use App\Models\Election;
use App\Models\Sponsor;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SponsorController extends Controller
{
    public function index()
    {
        return SponsorResource::collection(Sponsor::query()->global()->published()->get());
    }

    public function indexScoped(string $language, Election $election): AnonymousResourceCollection
    {
        return SponsorResource::collection(Sponsor::query()->relevantForElection($election)->published()->get());
    }
}
