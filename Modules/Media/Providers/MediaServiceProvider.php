<?php

declare(strict_types=1);

namespace Modules\Media\Providers;

use App\Providers\BaseModuleServiceProvider;

final class MediaServiceProvider extends BaseModuleServiceProvider
{
    /**
     * {@inheritDoc}
     */
    public function getModuleName(): string
    {
        return 'Media';
    }
}
