<?php

declare(strict_types=1);

namespace Tests;

use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Modules\User\Models\DisplayOption;
use Modules\User\Models\User;
use Tests\Traits\HasAuth;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions, HasAuth;

    /**
     * {@inheritDoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        try {
dd(User::factory()->create()->displayOptions);
            dd(DisplayOption::create([
                'user_id' => User::factory()->create()->id,
                'name' => 'test',
                'columns' => [
                    'first_name' => [
                        'value' => 'First namdasdasde',
                        'order' => 1,
                        'visible' => true,
                    ],
                    'last_name' => [],
                    'test' => [],
                ],
            ]));
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
        $this->withoutMiddleware(VerifyCsrfToken::class);
    }
}
