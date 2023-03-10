<?php

declare(strict_types=1);

namespace Modules\Communication\Providers;

use App\Providers\BaseModuleServiceProvider;
use Modules\Communication\Models\Call;
use Modules\Communication\Models\Comment;
use Modules\Communication\Models\CommunicationExtension;
use Modules\Communication\Models\CommunicationProvider;
use Modules\Communication\Models\DatabaseNotification;
use Modules\Communication\Models\Email;
use Modules\Communication\Models\EmailTemplates;
use Modules\Communication\Models\NotificationTemplate;
use Modules\Communication\Policies\CallPolicy;
use Modules\Communication\Policies\CommentPolicy;
use Modules\Communication\Policies\CommunicationExtensionPolicy;
use Modules\Communication\Policies\CommunicationProviderPolicy;
use Modules\Communication\Policies\EmailPolicy;
use Modules\Communication\Policies\EmailTemplatesPolicy;
use Modules\Communication\Policies\NotificationPolicy;
use Modules\Communication\Policies\NotificationTemplatePolicy;

class CommunicationServiceProvider extends BaseModuleServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected array $policies = [
        Comment::class => CommentPolicy::class,
        CommunicationProvider::class => CommunicationProviderPolicy::class,
        CommunicationExtension::class => CommunicationExtensionPolicy::class,
        Call::class => CallPolicy::class,
        Email::class => EmailPolicy::class,
        EmailTemplates::class => EmailTemplatesPolicy::class,
        NotificationTemplate::class => NotificationTemplatePolicy::class,
        DatabaseNotification::class => NotificationPolicy::class,
    ];

    /**
     * {@inheritDoc}
     */
    public function getModuleName(): string
    {
        return 'Communication';
    }

    /**
     * {@inheritDoc}
     */
    public function boot(): void
    {
        parent::boot();

        $this->registerViews();
    }
}
