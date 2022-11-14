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
        $rows = json_decode(file_get_contents('Modules/Geo/Database/data/country.json'), true);

        $rows = array_filter($rows, fn($value) => !is_null($value['iso3']));

        Country::query()->truncate();

        foreach ($rows as $row) {
            Country::query()->create([
                'name' => $row['name'],
                'iso2' => $row['iso2'],
                'iso3' => $row['iso3'],
            ]);
        }
    }
}
