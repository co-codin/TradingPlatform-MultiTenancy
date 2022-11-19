<?php

namespace Modules\Brand\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;


class CreateSchemaJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $slug
    ) {
    }

    public function handle()
    {
        DB::unprepared("CREATE SCHEMA IF NOT EXISTS new_schema");
    }
}
