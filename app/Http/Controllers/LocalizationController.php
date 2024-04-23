<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountryResource;
use App\Http\Resources\LanguageResource;
use App\Models\Country;
use App\Services\CrowdInTranslation;
use Illuminate\Http\JsonResponse;

class LocalizationController extends Controller
{
    public function index(CrowdInTranslation $crowdin): JsonResponse
    {

        $countries = Country::query()->published()->get();

        $languages = [
            [
                'id' => 'en', // kept for backwards compatibility
                'name' => 'English',
                'language_code' => 'en',
            ],
            ...$crowdin->listTargetLanguages(),
        ];

        return response()->json([
            'countries' => CountryResource::collection($countries),
            'languages' => LanguageResource::collection($languages),
        ]);
    }
}
