<?php

namespace Database\Seeders;

use App\Models\Country;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CountrySeeder extends Seeder
{

    public function run(): void
    {
        $countries = collect([
            'Austria',
            'Belgium',
            'Bulgaria',
            'Croatia',
            'Cyprus',
            'Czechia',
            'Denmark',
            'Estonia',
            'Finland',
            'France',
            'Germany',
            'Greece',
            'Hungary',
            'Ireland',
            'Italy',
            'Latvia',
            'Lithuania',
            'Luxembourg',
            'Malta',
            'Netherlands',
            'Poland',
            'Portugal',
            'Romania',
            'Slovakia',
            'Slovenia',
            'Spain',
            'Sweden',
        ]);

        Country::insert(
            $countries->map(fn ($name) => [
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'name' => $name,
                'flag' => sprintf('flags/%s.png', Str::lower($name)),
            ])->toArray()
        );

    }
}
