<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CountryStates;
use App\Models\Country;
use App\Models\CountryCities;
use Illuminate\Support\Facades\File;

class DRCitySeeder extends Seeder
{
    public function run()
    {
        $path = database_path('seeders/data/Ciudades RD.csv');
        if (!File::exists($path)) {
            throw new \RuntimeException("CSV not found at {$path}");
        }

        $content = File::get($path);
        $lines = preg_split('/\r\n|\n|\r/', $content);

        // Country map and country id for DO
        $countryMap = Country::pluck('id', 'iso2')->toArray();
        $iso = 'DO';
        if (!isset($countryMap[$iso])) {
            throw new \RuntimeException("Country DO not found in countries table (iso2: {$iso})");
        }
        $countryId = $countryMap[$iso];

        // Load states for this country and build normalized name -> id map
        $stateEntries = CountryStates::where('country_id', $countryId)->get();
        $stateMap = [];
        foreach ($stateEntries as $st) {
            $stateMap[$this->normalize($st->name)] = $st->id;
        }

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') continue;

            // Parse semicolon-separated CSV line
            $cols = str_getcsv($line, ';');
            $city = isset($cols[0]) ? trim($cols[0]) : '';
            $stateName = isset($cols[1]) ? trim($cols[1]) : '';

            // Skip header rows
            if ($city === '' || mb_strtolower($city) === 'ciudad' || mb_strtolower($stateName) === 'provincia') {
                continue;
            }

            // Remove leading numbering like "1. " if present
            $city = preg_replace('/^\d+\.\s*/', '', $city);
            $stateName = preg_replace('/^\d+\.\s*/', '', $stateName);

            // Clean non-breaking spaces
            $city = str_replace("\xc2\xa0", ' ', $city);
            $stateName = str_replace("\xc2\xa0", ' ', $stateName);

            $normState = $this->normalize($stateName);
            $stateId = $stateMap[$normState] ?? null;

            // Try fuzzy matching when exact normalized name not found
            if (!$stateId) {
                foreach ($stateEntries as $st) {
                    $norm = $this->normalize($st->name);
                    if ($norm === $normState || stripos($norm, $normState) !== false || stripos($normState, $norm) !== false) {
                        $stateId = $st->id;
                        break;
                    }
                }
            }

            if (!$stateId) {
                echo "Warning: state not found for '{$stateName}', skipping city '{$city}'\n";
                continue;
            }

            // Avoid duplicates
            $exists = CountryCities::where('country_id', $countryId)
                ->where('state_id', $stateId)
                ->where('name', $city)
                ->exists();
            if ($exists) continue;

            CountryCities::create([
                'country_id' => $countryId,
                'state_id' => $stateId,
                'name' => $city,
                'country_code' => $iso,
                'latitude' => null,
                'longitude' => null,
                'status' => 1,
            ]);
        }
    }

    private function normalize($s)
    {
        $s = mb_strtolower(trim((string) $s));
        $s = strtr($s, [
            'á' => 'a','é' => 'e','í' => 'i','ó' => 'o','ú' => 'u','ñ' => 'n','ü' => 'u',
            'Á' => 'a','É' => 'e','Í' => 'i','Ó' => 'o','Ú' => 'u','Ñ' => 'n','Ü' => 'u'
        ]);
        $s = preg_replace('/[^a-z0-9\s]/u', ' ', $s); // remove punctuation
        $s = preg_replace('/\s+/', ' ', $s);
        return trim($s);
    }
}
