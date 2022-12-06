<?php

namespace App\Jobs;

use App\Services\Tenant\DatabaseManipulator;
use App\Services\Tenant\Manager;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CreateTenantDatabase implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        protected string $brandSlug,
    ) {
        $this->onQueue(Manager::TENANT_CONNECTION_NAME);
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(DatabaseManipulator $databaseManipulator)
    {
        if (! $databaseManipulator->createSchema($this->brandSlug)) {
            throw new \RuntimeException('Database failed to be created.');
        }
    }

}
