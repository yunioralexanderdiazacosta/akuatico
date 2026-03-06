<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CountryStates;
use App\Models\Country;
use Illuminate\Support\Facades\File;

class CitySeeder extends Seeder
{
    public function run()
    {
        // Cargar ciudades desde JSON específico (Puerto Rico)
        $json = File::get(database_path("seeders/data/cities_us_pr.json"));
        $cities = json_decode($json);

        // Mapa: iso2 -> country_id
        $countryMap = Country::pluck("id", "iso2")->toArray();

        // Mapa de estados por país: country_id => [ state_name => state_id ]
        $stateEntries = CountryStates::all();
        $stateMap = [];
        foreach ($stateEntries as $st) {
            $cid = $st->country_id;
            $name = $st->name;
            if (!isset($stateMap[$cid])) {
                $stateMap[$cid] = [];
            }
            $stateMap[$cid][$name] = $st->id;
        }

        foreach ($cities as $city) {
            // Encontrar país
            $countryIso = strtoupper($city->country_code);
            if (!isset($countryMap[$countryIso])) {
                throw new \RuntimeException(
                    "Country not found for code: {$countryIso}",
                );
            }
            $countryId = $countryMap[$countryIso];

            // Intentar mapear estado por nombre; fallback a código de estado si aplica
            $stateName = $city->state_name ?? null;
            if (!$stateName) {
                $stateName = $city->state_code ?? null;
            }

            if ($stateName && isset($stateMap[$countryId][$stateName])) {
                $stateId = $stateMap[$countryId][$stateName];
            } else {
                // Intentar buscar por código de estado si existe en el conjunto
                $stateId = null;
                $stateCode = $city->state_code ?? null;
                if ($stateCode) {
                    foreach ($stateEntries as $st) {
                        if (
                            (string) $st->country_id === (string) $countryId &&
                            ((string) $st->state_code === (string) $stateCode ||
                                (string) $st->code === (string) $stateCode)
                        ) {
                            $stateId = $st->id;
                            break;
                        }
                    }
                }
                if (!$stateId) {
                    throw new \RuntimeException(
                        "State not found for city {$city->name} (state: {$stateName}, country: {$countryIso})",
                    );
                }
            }

            \App\Models\CountryCities::create([
                "country_id" => $countryId,
                "state_id" => $stateId,
                "name" => $city->name,
                "country_code" => $city->country_code,
                "latitude" => $city->latitude ?? null,
                "longitude" => $city->longitude ?? null,
                "status" => 1,
            ]);
        }
    }
}
