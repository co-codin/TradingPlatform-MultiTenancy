<?php
declare(strict_types=1);

namespace Modules\Brand\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class MigrateBrandDBJob implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use InteractsWithQueue;
    use SerializesModels;

    protected ?string $db = null;

    protected ?array $tables = null;

    public function __construct(string $db, array $tables)
    {
        $this->db = $db;
        $this->tables = $tables;
    }

    public function handle(): void
    {
//        Config::set('config.databse.connections.pgsql.database', $this->db);
//        putenv("DB_DATABASE=$this->db");
        $migrationsPath = 'Modules/Brand/DB/Migrations/';

        foreach ($this->tables as $table) {
            Artisan::call(sprintf(
                'brand-migrate --path=%s --database=%s',
                "{$migrationsPath}create_{$table}_table.php",
                $this->db
            ));

//            dd($result);
        }
    }
}
