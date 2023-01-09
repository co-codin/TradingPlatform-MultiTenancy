<?php

namespace App\Providers;

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\ServiceProvider;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Broadcast::routes(['middleware' => ['web', 'auth:api']]);
        Broadcast::routes(['prefix' => 'customer', 'middleware' => ['web', 'auth:web-customer,api-customer']]);

        require base_path('routes/channels.php');
    }
}
