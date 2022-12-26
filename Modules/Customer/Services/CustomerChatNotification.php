<?php

declare(strict_types=1);

namespace Modules\Customer\Services;

use LogicException;
use Modules\Customer\Events\CustomerChatNotificationEvent;
use Modules\Customer\Models\CustomerChatMessage;

final class CustomerChatNotification
{
    /**
     * Chat event execution
     *
     * @param  int $user_id
     * @return int $total
     */
    public function execution(int $user_id): int
    {
        $customerChatMessage = CustomerChatMessage::where('customer_id', $user_id)
            ->where('initiator_type', 'user')
            ->groupBy('user_id')
            ->selectRaw('count(*) as total, user_id')
            ->get();

        $unreadList = CustomerChatMessage::where('read', 0)
            ->where('initiator_type', 'customer')
            ->groupBy('customer_id')
            ->selectRaw('count(*) as unread, customer_id')
            ->get()->toArray();

        $total = 0;
        foreach ($unreadList as $unread) {
            $total += $unread['unread'];
        }

        foreach ($customerChatMessage as $user) {
            CustomerChatNotificationEvent::dispatch($user->user_id, $unreadList, $total);
        }

        return $total;
    }
}
