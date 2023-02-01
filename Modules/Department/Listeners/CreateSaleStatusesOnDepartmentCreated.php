<?php

declare(strict_types=1);

namespace Modules\Department\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Department\Events\DepartmentCreated;
use Modules\Sale\Enums\SaleStatusNameEnum;

final class CreateSaleStatusesOnDepartmentCreated// implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param  DepartmentCreated  $event
     * @return void
     */
    public function handle(DepartmentCreated $event): void
    {
        foreach (SaleStatusNameEnum::getValues() as $name) {
            $event->department->saleStatuses()->create([
                'name' => $name,
                'title' => ucfirst(implode(' ', explode('_', $name))),
                'color' => fake()->hexColor(),
                'is_active' => true,
            ]);
        }
    }
}
