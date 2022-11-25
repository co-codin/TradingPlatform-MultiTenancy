<?php

namespace Modules\Geo\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Geo\Models\Country;

class CountryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $countries = json_decode(file_get_contents('Modules/Geo/Database/data/country.json'), true);
        $currencies = collect(json_decode(file_get_contents('Modules/Geo/Database/data/currencies.json')));

        $rows = array_filter($countries, fn($value) => ! is_null($value['iso3']));

        foreach ($rows as $row) {
            Country::query()->updateOrCreate([
                'name' => $row['name'],
                'iso2' => $row['iso2'],
                'iso3' => $row['iso3'],
                'currency' => $currencies->where('country', $row['name'])->first()?->code,
            ]);
        }
    }
}
