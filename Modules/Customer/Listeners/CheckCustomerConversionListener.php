<?php

declare(strict_types=1);

namespace Modules\Customer\Listeners;

use Exception;
use Modules\Customer\Events\CustomerSaving;

final class CheckCustomerConversionListener
{
    /**
     * @param CustomerSaving $event
     * @return void
     * @throws Exception
     */
    public function handle(CustomerSaving $event): void
    {
        if ($event->customer->getOriginal('first_conversion_user_id')) { // has first_conversion_user_id value
            $event->customer->first_conversion_user_id = $event->customer->getOriginal('first_conversion_user_id');
        } elseif ($event->customer->affiliate_user_id && ! $event->customer->first_conversion_user_id) { // given affiliate_user_id, but has not first_conversion_user_id
            $event->customer->first_conversion_user_id = $event->customer->affiliate_user_id;
        } elseif (
            $event->customer->first_conversion_user_id
            && $event->customer->affiliate_user_id
            && $event->customer->affiliate_user_id !== $event->customer->first_conversion_user_id
        ) { // given first_conversion_user_id and affiliate_user_id, but they not equal
            throw new Exception('Cant update conversion.');
        }
    }
}
