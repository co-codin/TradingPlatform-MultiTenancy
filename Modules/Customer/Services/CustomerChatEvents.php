<?php

declare(strict_types=1);

namespace Modules\Customer\Services;

use LogicException;
use Modules\Customer\Events\CustomerChatEvent;
use Modules\Customer\Events\CustomerChatNotificationEvent;
use Modules\Customer\Http\Resources\Chat\CustomerChatMessageResource;
use Modules\Customer\Models\CustomerChatMessage;
use Modules\Customer\Services\CustomerChatNotification;

final class CustomerChatEvents
{

    /**
     * @param  CustomerChatNotification  $customerChatNotification
     */
    public function __construct(
        protected CustomerChatNotification $customerChatNotification,
    ) {
    }
    /**
     * Chat event execution
     *
     * @param  int $user_id
     * @param  CustomerChatMessageResource  $return
     * @return CustomerChatMessage
     */
    public function execution(int $user_id, CustomerChatMessageResource  $return): bool
    {
        CustomerChatEvent::dispatch($user_id, $return);

        $this->customerChatNotification->execution($user_id);

        return true;
    }
}
