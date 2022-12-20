<?php

namespace Modules\Communication\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Communication\Models\Email;
use Modules\Communication\Policies\EmailPolicy;
use Modules\Communication\Models\EmailTemplates;
use Modules\Communication\Policies\EmailTemplatesPolicy;

class CommunicationServiceProvider extends BaseModuleServiceProvider
{
    /**
     * @var array
     */
    protected array $policies = [
        Email::class => EmailPolicy::class,
        EmailTemplates::class => EmailTemplatesPolicy::class,
    ];
    /**
     * {@inheritDoc}
     */
    public function getModuleName(): string
    {
        return 'Communication';
    }
}
