<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountryResource;
use App\Models\Country;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CountryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return CountryResource::collection(Country::query()->get());
    }
}
