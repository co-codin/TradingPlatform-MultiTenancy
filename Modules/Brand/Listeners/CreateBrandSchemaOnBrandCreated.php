<?php

declare(strict_types=1);

namespace Modules\Brand\Listeners;

use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Modules\Brand\Events\BrandCreated;
use Modules\User\Models\User;

final class CreateBrandSchemaOnBrandCreated //implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param BrandCreated $event
     * @return void
     */
    final public function handle(BrandCreated $event): void
    {
        try {
//            $database = Config::get('database.connections.pgsql.database');
//            dump($event->brand->slug);
//            DB::commit();
            Schema::createDatabase($event->brand->slug);
//            DB::purge('pgsql');
//            Config::set('database.connections.pgsql.database', $event->brand->slug);
//            DB::purge('pgsql');
        } catch (\Throwable $e) {
            dd($e->getMessage());
        }
//
//        $schema = Str::snake(Str::camel($event->brand->slug));
//        DB::unprepared("CREATE SCHEMA IF NOT EXISTS {$schema}");
    }
}
