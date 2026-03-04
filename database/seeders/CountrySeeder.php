<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country; // Asegúrate de tener el modelo creado
use Illuminate\Support\Facades\File;

class CountrySeeder extends Seeder
{
    public function run()
    {
        $json = File::get(database_path("seeders/data/countries.json"));
        $countries = json_decode($json);

        foreach ($countries as $country) {
            Country::create([
                "iso2" => $country->iso2,
                "name" => $country->name,
                "phone_code" => $country->phonecode,
                "iso3" => $country->iso3,
                "region" => $country->region,
                "subregion" => $country->subregion,
                "latitude" => $country->latitude,
                "longitude" => $country->longitude,
            ]);
        }
    }
}
