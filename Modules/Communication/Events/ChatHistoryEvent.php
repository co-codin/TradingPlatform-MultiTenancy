<?php

declare(strict_types=1);

namespace Modules\Communication\Events;

use Illuminate\Queue\SerializesModels;
use App\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

final class ChatHistoryEvent implements ShouldBroadcast
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(
        public $user_id,
        public $customer_id,
        public $message,
    ) {
    }
    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('chat.' . $this->customer_id);
    }

    public function broadcastAs()
    {
        return 'chat_history_message';
    }

    public function broadcastWith()
    {
        return ['message' => $this->message, 'user' => $this->user_id];
    }
}
