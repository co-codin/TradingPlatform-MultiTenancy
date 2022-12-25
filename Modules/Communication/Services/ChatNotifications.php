<?php

declare(strict_types=1);

namespace Modules\Communication\Services;

use LogicException;
use Modules\Communication\Events\ChatEvent;
use Modules\Communication\Events\ChatNotificationEvent;
use Modules\Communication\Http\Resources\Chat\ChatMessageResource;
use Modules\Communication\Models\ChatMessage;

final class ChatNotifications
{
    /**
     * Chat notification event execution
     *
     * @param  int $customer_id
     * @return int $total
     */
    public function execution(int $customer_id): int
    {
        $unreadList = ChatMessage::where('customer_id', $customer_id)
            ->where('read', 0)
            ->where('initiator_type', 'user')
            ->groupBy('customer_id')
            ->selectRaw('count(*) as unread, customer_id')
            ->get()->toArray();

        $total = 0;
        foreach ($unreadList as $unread) {
            $total += $unread['unread'];
        }

        ChatNotificationEvent::dispatch($customer_id, $unreadList, $total);

        return $total;
    }
}
