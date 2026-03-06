<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CountryStates;
use App\Models\Country;
use Illuminate\Support\Facades\File;

class StateSeeder extends Seeder
{
    public function run()
    {
        $json = File::get(database_path("seeders/data/states.json"));
        $states = json_decode($json);

        // Build in-memory map: iso2 (country_code) -> country_id
        $countryMap = Country::pluck('id', 'iso2')->toArray();

        foreach ($states as $state) {
            $countryIso = strtoupper($state->country_code);
            if (!isset($countryMap[$countryIso])) {
                throw new \RuntimeException("Country not found for code: {$countryIso}");
            }

            CountryStates::create([
                "country_id" => $countryMap[$countryIso],
                "name" => $state->name,
                "country_code" => $state->country_code,
                "status" => 1,
            ]);
        }
    }
}
