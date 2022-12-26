<?php

declare(strict_types=1);

namespace Modules\Customer\Events;

use Illuminate\Queue\SerializesModels;
use App\Message;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

final class CustomerChatNotificationEvent implements ShouldBroadcast
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
        public $message,
        public $total,
    ) {
    }
    /**
     * Get the channels the event should be broadcast on.
     *
     * @return PrivateChannel
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('chatnotification.' . $this->user_id);
    }

    public function broadcastAs()
    {
        return 'chat_notification';
    }
    public function broadcastWith()
    {
        return ['notification' => $this->message, 'total' => $this->total];
    }
}
