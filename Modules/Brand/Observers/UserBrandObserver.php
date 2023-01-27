<?php

declare(strict_types=1);

namespace Modules\Brand\Observers;

use Modules\Brand\Models\UserBrand;

final class UserBrandObserver
{
    /**
     * Handle the Brand "saving" event.
     *
     * @param  UserBrand  $userBrand
     * @return void
     */
    public function saving(UserBrand $userBrand): void
    {
        UserBrand::withoutEvents(function () use ($userBrand) {
            UserBrand::query()->where([
                ['user_id', '=', $userBrand->user_id],
                ['brand_id', '=', $userBrand->brand_id],
                ['is_default', '=', true],
            ])->update(['is_default' => false]);
        });
    }
}
