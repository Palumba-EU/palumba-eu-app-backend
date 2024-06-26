<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountryResource;
use App\Http\Resources\LanguageResource;
use App\Models\Country;
use App\Models\Language;
use App\Services\CrowdInTranslation;
use Illuminate\Http\JsonResponse;

class LocalizationController extends Controller
{
    public function index(CrowdInTranslation $crowdin): JsonResponse
    {

        $countries = Country::query()->published()->get();
        $languages = Language::query()->published()->get();

        return response()->json([
            'countries' => CountryResource::collection($countries),
            'languages' => LanguageResource::collection($languages),
        ]);
    }
}
