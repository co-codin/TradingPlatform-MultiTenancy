<?php

namespace Modules\Token\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Token\Models\Token;
use Modules\Token\Policies\TokenPolicy;

class TokenServiceProvider extends BaseModuleServiceProvider
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

    public function boot(): void
    {
        parent::boot();
        $this->loadMigrationsFrom(module_path($this->getModuleName(), 'Database/Migrations'));
    }
}
