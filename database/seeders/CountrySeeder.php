<?php

namespace Database\Seeders;

use App\Models\Country;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class CountrySeeder extends Seeder
{
    public function run(): void
    {
        $countries = collect([
            ['name' => 'Austria', 'code' => 'at'],
            ['name' => 'Belgium', 'code' => 'be'],
            ['name' => 'Bulgaria', 'code' => 'bg'],
            ['name' => 'Croatia', 'code' => 'hr'],
            ['name' => 'Cyprus', 'code' => 'cy'],
            ['name' => 'Czechia', 'code' => 'cz'],
            ['name' => 'Denmark', 'code' => 'dk'],
            ['name' => 'Estonia', 'code' => 'ee'],
            ['name' => 'Finland', 'code' => 'fi'],
            ['name' => 'France', 'code' => 'fr'],
            ['name' => 'Germany', 'code' => 'de'],
            ['name' => 'Greece', 'code' => 'gr'],
            ['name' => 'Hungary', 'code' => 'hu'],
            ['name' => 'Ireland', 'code' => 'ie'],
            ['name' => 'Italy', 'code' => 'it'],
            ['name' => 'Latvia', 'code' => 'lv'],
            ['name' => 'Lithuania', 'code' => 'lt'],
            ['name' => 'Luxembourg', 'code' => 'lu'],
            ['name' => 'Malta', 'code' => 'mt'],
            ['name' => 'Netherlands', 'code' => 'nl'],
            ['name' => 'Poland', 'code' => 'pl'],
            ['name' => 'Portugal', 'code' => 'pt'],
            ['name' => 'Romania', 'code' => 'ro'],
            ['name' => 'Slovakia', 'code' => 'sk'],
            ['name' => 'Slovenia', 'code' => 'si'],
            ['name' => 'Spain', 'code' => 'es'],
            ['name' => 'Sweden', 'code' => 'se'],
        ]);

        Country::insert(
            $countries->map(function ($country) {
                $flagPath = sprintf('flags/%s.svg', $country['code']);

                // Loads flags from https://github.com/lipis/flag-icons which is released under MIT License
                $url = sprintf('https://raw.githubusercontent.com/lipis/flag-icons/main/flags/4x3/%s.svg', $country['code']);
                file_put_contents(Storage::disk('public')->path($flagPath), file_get_contents($url));

                return [
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'flag' => $flagPath,
                    ...$country,
                ];
            })->toArray()
        );

    }
}
