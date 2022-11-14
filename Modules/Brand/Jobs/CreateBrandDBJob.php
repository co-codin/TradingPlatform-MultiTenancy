<?php

namespace Modules\Brand\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;


class CreateBrandDBJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(
        protected string $slug
    ) {
    }

    public function handle()
    {
        if (DB::selectOne("SELECT 1 FROM pg_database WHERE datname = ?", [$this->slug]) === null) {
            DB::commit();
            DB::statement("CREATE DATABASE $this->slug");
        }
    }
}
