<?php

declare(strict_types=1);

namespace Modules\Brand\Commands;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Console\Migrations\MigrateCommand;
use Illuminate\Database\Migrations\Migrator;

final class BrandMigrationCommand extends MigrateCommand
{
    protected $signature = 'brand:migrate {--database= : The database connection to use}
                {--path=* : The path(s) to the migrations files to be executed}';

    public function __construct(Migrator $migrator, Dispatcher $dispatcher)
    {
        parent::__construct($migrator, $dispatcher);
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        if (! $this->confirmToProceed()) {
            return 1;
        }

        $this->migrator->usingConnection($this->option('database'), function () {
            $this->migrator->run($this->option('path'));
        });

        return 0;
    }
}
