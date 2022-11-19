<?php

declare(strict_types=1);

namespace Modules\Language\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Language\Models\Language;
use Modules\Language\Policies\LanguagePolicy;

final class LanguageServiceProvider extends BaseModuleServiceProvider
{
    /**
     * @var array
     */
    protected array $policies = [
        Language::class => LanguagePolicy::class,
    ];

    /**
     * @inheritDoc
     */
    public function getModuleName(): string
    {
        return 'Language';
    }
}
