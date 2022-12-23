<?php

declare(strict_types=1);

namespace Modules\Communication\Services;

use LogicException;
use Modules\Communication\Dto\ChatMessageDto;
use Modules\Communication\Models\ChatMessage;
use Modules\User\Models\User;

final class ChatStorage
{
    /**
     * Store chat message.
     *
     * @param  User  $user
     * @param  ChatMessageDto  $dto
     * @return ChatMessage
     */
    public function store(User $user, ChatMessageDto $dto): ChatMessage
    {
        $chatMessage = $dto->toArray();
        $chatMessage['user_id'] = $user->id;
        $chatMessage['initiator_id'] = $user->id;
        $chatMessage['initiator_type'] = 'user';
        $chatMessage['read'] = 0;

        $message = ChatMessage::create($chatMessage);

        if (!$message) {
            throw new LogicException(__('Can not create chat message'));
        }

        return $message;
    }
}
