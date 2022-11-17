<?php

namespace Modules\Brand\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class MigrateDataJob implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use InteractsWithQueue;
    use SerializesModels;

    public function __construct(
        private readonly string $db
    )
    {}

    public function handle()
    {
        $users = auth('sanctum')->user()->ancestors()->merge(auth('sanctum')->user()->descendants());

//        DB::connection($this->db)->insert($users);
    }
}
