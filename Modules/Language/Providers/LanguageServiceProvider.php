<?php

namespace Modules\Language\Providers;

use App\Providers\BaseModuleServiceProvider;

class LanguageServiceProvider extends BaseModuleServiceProvider
{
    /**
     * @inheritDoc
     */
    public function getModuleName(): string
    {
        return 'Language';
    }
}
