<?php

namespace Modules\Currency\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Currency\Models\Currency;

class CurrencyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $currencies = collect(
            json_decode(file_get_contents('Modules/Currency/Database/data/currencies.json'))
        )
        ->filter(function ($currency) {
            return ! empty($currency->currency)
                && ! empty($currency->code)
                && ! empty($currency->symbol);
        });

        foreach ($currencies as $currency) {
            Currency::query()->updateOrCreate([
                'name' => $currency->currency,
                'iso3' => $currency->code,
                'symbol' => $currency->symbol,
                'is_available' => true,
            ]);
        }
    }
}
