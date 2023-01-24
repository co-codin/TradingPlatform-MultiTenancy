<?php

declare(strict_types=1);

namespace Modules\User\Listeners;

use App\Models\Model;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Modules\User\Events\UserCreated;
use Modules\User\Models\DisplayOption;

final class CreateDisplayOptionOnUserCreated implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event): void
    {
        foreach (Model::get() as $model) {
            $event->user
                ->displayOptions()
                ->create([
                    'model_id' => $model->id,
                    'columns' => [],
                    'per_page' => DisplayOption::DEFAULT_PER_PAGE,
                ]);
        }
    }
}
