<?php

declare(strict_types=1);

namespace Modules\User\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Role\Models\Model;
use Modules\User\Events\UserCreated;

final class CreateDisplayOptionOnUserCreated // implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  UserCreated  $event
     * @return void
     */
    final public function handle(UserCreated $event): void
    {
        foreach (Model::get() as $model) {
            $event->user
                ->displayOptions()
                ->create([
                    'model_id' => $model->id,
                    'columns' => [],
                    'settings' => [],
                ]);
        }
    }
}
