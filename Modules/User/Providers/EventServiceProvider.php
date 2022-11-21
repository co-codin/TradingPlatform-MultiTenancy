<?php

declare(strict_types=1);

namespace Modules\User\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Modules\User\Listeners\CreateDisplayOptionOnUserCreated;
use Modules\User\Events\UserCreated;

final class EventServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    protected $listen = [
        UserCreated::class => [
            CreateDisplayOptionOnUserCreated::class,
        ],
    ];
}
