<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DbWipeAll extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:wipe-all';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Wipe all db schemas';

    /**
     * Execute the console command.
     *
     */
    public function handle(): void
    {
        DB::statement(<<<'SQL'
            do
            $body$
                declare
                    f_rec record;
                begin

                    for f_rec in
                        SELECT schema_name::text
                        FROM information_schema.schemata
                        where schema_name not in ('pg_toast', 'pg_catalog', 'information_schema')
                        loop
                            execute 'DROP SCHEMA ' || f_rec.schema_name || ' CASCADE';
                        end loop;
                    execute 'CREATE SCHEMA public';
                    execute 'GRANT ALL ON SCHEMA public TO public';
                end
            $body$
            language 'plpgsql';
            SQL
        );
    }
}
