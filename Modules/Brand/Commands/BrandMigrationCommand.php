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
                {--force : Force the operation to run when in production}
                {--path=* : The path(s) to the migrations files to be executed}
                {--realpath : Indicate any provided migration file paths are pre-resolved absolute paths}
                {--schema-path= : The path to a schema dump file}
                {--pretend : Dump the SQL queries that would be run}
                {--seed : Indicates if the seed task should be re-run}
                {--seeder= : The class name of the root seeder}
                {--step : Force the migrations to be run so they can be rolled back individually}';

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
                $migrations = $this->migrator->setOutput($this->output)
                    ->run($this->getMigrationPaths(), [
                        'pretend' => $this->option('pretend'),
                        'step' => $this->option('step'),
                    ]);
dump($migrations);
                if ($this->option('seed') && ! $this->option('pretend')) {
                    $this->call('db:seed', [
                        '--class' => $this->option('seeder') ?: 'Database\\Seeders\\DatabaseSeeder',
                        '--force' => true,
                    ]);
                }
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

        if (! $this->migrator->hasRunAnyMigrations() && ! $this->option('pretend')) {
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
