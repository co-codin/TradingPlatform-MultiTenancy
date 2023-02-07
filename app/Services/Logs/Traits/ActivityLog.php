<?php

declare(strict_types=1);

namespace App\Services\Logs\Traits;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Contracts\Activity;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Multitenancy\Models\Tenant;

trait ActivityLog
{
    use LogsActivity;

    /**
     * getActivitylogOptions
     *
     * @return LogOptions
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->dontLogIfAttributesChangedOnly([
                'created_at',
                'updated_at',
            ])
            ->logOnlyDirty();
    }

    /**
     * tapActivity
     *
     * @param  Activity $activity
     * @param  string $eventName
     * @return void
     */
    public function tapActivity(Activity $activity, string $eventName): void
    {
        $activity->brand_id = Tenant::current()?->id;
    }
}
