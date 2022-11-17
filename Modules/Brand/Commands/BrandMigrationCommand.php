<?php
declare(strict_types=1);

namespace Modules\Brand\Commands;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Database\Console\Migrations\MigrateCommand;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Support\Facades\Config;

final class BrandMigrationCommand extends MigrateCommand
{
    protected $signature = 'brand-migrate {--database= : The database connection to use}
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
    public function handle()
    {
        if (! $this->confirmToProceed()) {
            return 1;
        }

        $this->migrator->usingConnection('pgsql', function () {
            $this->intoDatabase(function () {
                $this->migrator->setOutput($this->output);
            });
        });

        return 0;
    }

    /**
     * Prepare the migration database for running.
     *
     * @return void
     */
    protected function prepareDatabase(): void
    {
        Config::set('database.connections.pgsql.database', $this->option('database'));

        if (! $this->migrator->hasRunAnyMigrations()) {
            $this->loadSchemaState();
        }
    }

    protected function intoDatabase(callable $function)
    {
        $database = Config::get('database.connections.pgsql.database');

        $this->prepareDatabase();

        call_user_func($function);

        Config::set('database.connections.pgsql.database', $database);
    }
}
