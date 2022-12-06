<?php

declare(strict_types=1);

namespace Modules\Brand\Jobs\Seeders;

use App\Contracts\HasTenantDBConnection;
use App\Services\Tenant\Manager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Modules\Brand\Events\Tenant\BrandTenantIdentified;
use Modules\Brand\Models\Brand;
use Modules\User\Models\User;

class SeedUserIntoTenantDBJob implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use InteractsWithQueue;

    /**
     * @param HasTenantDBConnection $tenant
     */
    public function __construct(
        public readonly HasTenantDBConnection $tenant,
    ) {
        $this->onQueue(Manager::TENANT_CONNECTION_NAME);
    }

    /**
     * @return bool
     */
    public function handle(): bool
    {
        BrandTenantIdentified::dispatch($this->tenant);

        $userData = collect();

        app(Manager::class)->escapeTenant(function () use (&$userData) {
            foreach ($this->tenant->users()->get() as $user) {
//                $this->mergeNode('ancestors', $userData, $user);
//                $this->mergeNode('descendants', $userData, $user);
            }
        });

//        foreach ($userData->unique('id') as $user) {
//            User::insert($user->makeVisible(['password'])->toArray());
//        }

        return true;
    }

    /**
     * @param string $key
     * @param $userData
     * @param $user
     * @return void
     */
    private function mergeNode(string $key, &$userData, $user): void
    {
        $methodName = 'get'.ucfirst($key);
        $ancestors = $user->{$methodName}();

        while ($ancestors->isNotEmpty()) {
            $userData = $userData->merge($ancestors);

            foreach ($ancestors as $ancestor) {
                $this->mergeNode($key, $userData, $ancestor);
            }
            $ancestors = collect();
        }
    }
}
