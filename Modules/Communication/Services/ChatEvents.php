<?php

declare(strict_types=1);

namespace Modules\Communication\Services;

use LogicException;
use Modules\Communication\Events\ChatEvent;
use Modules\Communication\Events\ChatNotificationEvent;
use Modules\Communication\Http\Resources\Chat\ChatMessageResource;
use Modules\Communication\Models\ChatMessage;
use Modules\Communication\Services\ChatNotifications;


final class ChatEvents
{
    /**
     * @param  ChatNotifications  $chatNotifications
     */
    public function __construct(
        protected ChatNotifications $chatNotifications,
    ) {
    }

    /**
     * Chat event execution
     *
     * @param  int $customer_id
     * @param  int $user_id
     * @param  ChatMessageResource  $return
     * @return ChatMessage
     */
    public function execution(int $customer_id, int $user_id, ChatMessageResource  $return): bool
    {
        ChatEvent::dispatch($user_id, $customer_id, $return);

        $this->chatNotifications->execution($customer_id);

        return true;
    }
}
