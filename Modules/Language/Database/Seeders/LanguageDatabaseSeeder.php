<?php

namespace Modules\Language\Database\Seeders;

use Illuminate\Database\Seeder;

class LanguageDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            LanguageTableSeeder::class,
        ]);
    }
}
