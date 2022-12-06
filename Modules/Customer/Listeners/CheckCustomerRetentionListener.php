<?php

declare(strict_types=1);

namespace Modules\Customer\Listeners;

use Exception;
use Modules\Customer\Events\CustomerSaving;

final class CheckCustomerRetentionListener
{
    /**
     * @param CustomerSaving $event
     * @return void
     * @throws Exception
     */
    public function handle(CustomerSaving $event): void
    {
        if ($event->customer->getOriginal('first_retention_user_id')) { // has first_retention_user_id value
            $event->customer->first_retention_user_id = $event->customer->getOriginal('first_retention_user_id');
        } elseif ($event->customer->retention_user_id && !$event->customer->first_retention_user_id) { // given retention_user_id, but has not first_retention_user_id
            $event->customer->first_retention_user_id = $event->customer->retention_user_id;
        } elseif (
            $event->customer->first_retention_user_id
            && $event->customer->retention_user_id
            && $event->customer->retention_user_id !== $event->customer->first_retention_user_id
        ) { // given first_retention_user_id and retention_user_id, but they not equal
            throw new Exception('Cant update retention.');
        }
    }
}
