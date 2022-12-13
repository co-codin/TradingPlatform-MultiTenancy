<?php

declare(strict_types=1);

namespace Modules\Token\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Token\Models\Token;
use Modules\Token\Policies\TokenPolicy;

final class TokenServiceProvider extends BaseModuleServiceProvider
{
    /**
     * @inheritdoc
     */
    protected array $policies = [
        Token::class => TokenPolicy::class,
    ];

    /**
     * @inheritDoc
     */
    public function getModuleName(): string
    {
        return 'Token';
    }

    /**
     * {@inheritDoc}
     */
    public function boot(): void
    {
        parent::boot();

        $this->loadMigrations();
    }
}
