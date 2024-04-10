<?php

namespace App\Http\Controllers;

use App\Http\Resources\CountryResource;
use App\Http\Resources\LanguageResource;
use App\Models\Country;
use Illuminate\Http\JsonResponse;

class LocalizationController extends Controller
{
    public function index(): JsonResponse
    {

        $countries = Country::query()->published()->get();
        $languages = [
            [
                'id' => 1,
                'name' => 'English',
                'language_code' => 'en',
            ],
        ];

        return response()->json([
            'countries' => CountryResource::collection($countries),
            'languages' => LanguageResource::collection($languages),
        ]);
    }
}
