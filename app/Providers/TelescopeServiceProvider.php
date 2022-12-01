<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\EntryType;
use Illuminate\Support\Collection;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        Telescope::night();

        $this->hideSensitiveRequestDetails();

        $this->filterEntries();

        $this->filterBatches();

        Telescope::tag(function (IncomingEntry $entry) {

            if($entry->type === EntryType::REQUEST) {
                return ['status:' . $entry->content['response_status']];
            }

            return [];
        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     *
     * @return void
     */
    protected function hideSensitiveRequestDetails()
    {
        if (config('app.env') === 'local') {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    protected function gate()
    {
        Gate::define('viewTelescope', fn($user) => true);
    }

    protected function filterEntries()
    {
        Telescope::filter(function (IncomingEntry $entry) {
            if (config('app.env') === 'local') {
                return true;
            }

            return $this->isLoggableRequest($entry) ||
                $entry->isReportableException() ||
                $entry->isFailedRequest() ||
                $entry->isFailedJob() ||
                $entry->isScheduledTask() ||
                $entry->hasMonitoredTag();
        });
    }

    protected function isLoggableRequest(IncomingEntry $entry) : bool
    {
        if($entry->type !== EntryType::REQUEST) {
            return false;
        }

        $route = request()->route();

        return in_array(request()->method(), ['POST', 'PUT', 'PATCH', 'DELETE'])
            || ($entry->content['response_status'] ?? null) == 404;
    }

    protected function  filterBatches()
    {
        Telescope::filterBatch(function (Collection $entries) {
            if (config('app.env') === 'local') {
                return true;
            }

            return $entries->contains(function (IncomingEntry $entry) {
                return $this->isLoggableRequest($entry) ||
                    $entry->isReportableException() ||
                    $entry->isFailedRequest() ||
                    $entry->isFailedJob() ||
                    $entry->isScheduledTask() ||
                    $entry->type === EntryType::MAIL ||
                    $entry->type === EntryType::NOTIFICATION ||
                    $entry->hasMonitoredTag();
            });
        });
    }
}
