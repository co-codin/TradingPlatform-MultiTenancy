<?php

declare(strict_types=1);

namespace Modules\Communication\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class NotificationEvent implements ShouldBroadcastNow
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
        public $notifiable_type,
        public $notifiable_id,
        public $data,
    ) {
        //
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return PrivateChannel
     */
    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel($this->notifiable_type . 'notifications.' . $this->notifiable_id);
    }

    public function broadcastAs()
    {
        return 'notification';
    }

    public function broadcastWith()
    {
        return $this->data;
    }
}
