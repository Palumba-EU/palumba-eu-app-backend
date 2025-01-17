<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountryResource;
use App\Http\Resources\LanguageResource;
use App\Models\Country;
use App\Models\Election;
use App\Models\Language;
use Illuminate\Http\JsonResponse;

class LocalizationController extends Controller
{
    public function index(): JsonResponse
    {
        $countries = Country::query()->published()->country()->get();
        $languages = Language::query()->published()->get();

        return response()->json([
            'countries' => CountryResource::collection($countries),
            'languages' => LanguageResource::collection($languages),
        ]);
    }

    public function indexScoped(string $language, Election $election): JsonResponse
    {
        $countries = Country::query()->published()->parent($election->country)->get();
        $languages = $election->availableLanguages()->published()->get();

        return response()->json([
            'countries' => CountryResource::collection($countries),
            'languages' => LanguageResource::collection($languages),
        ]);
    }
}
