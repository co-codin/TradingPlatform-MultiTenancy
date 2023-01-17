<?php

namespace Modules\Language\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Language\Models\Language;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languages = collect(json_decode(file_get_contents('Modules/Language/Database/data/languages.json')));

        foreach ($languages as $language) {
            Language::query()->updateOrCreate([
                'name' => $language->language,
                'code' => $language->code,
            ]);
        }
    }
}
