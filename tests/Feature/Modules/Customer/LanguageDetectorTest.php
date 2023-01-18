<?php

declare(strict_types=1);

namespace Tests\Feature\Modules\Customer;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Modules\Customer\Services\LanguageDetector;
use Modules\Language\Database\Seeders\LanguageTableSeeder;
use Tests\TestCase;

final class LanguageDetectorTest extends TestCase
{
    use DatabaseMigrations;

    private LanguageDetector $detector;

    private array $fullNameSet = [
        'en' => 'Thomas Moore',
        'pl' => 'Zdzisław Abadżijew',
        'tr' => 'Emirşan Şener',
        'de' => 'Hans Jürgen Klemt',
        'it' => 'Giulia Fattori',
    ];

    /**
     * @test
     */
    public function detect_success(): void
    {
        foreach ($this->fullNameSet as $lang => $name) {
            self::assertEquals($lang, $this->detector->detectBest($name));
        }
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(LanguageTableSeeder::class);
        $this->detector = $this->app->make(LanguageDetector::class);
    }
}
