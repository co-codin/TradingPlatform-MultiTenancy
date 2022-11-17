<?php

namespace Modules\Brand\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Modules\Brand\Models\Brand;
use Modules\User\Models\User;

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

//        $userIds = auth('sanctum')->user()->brands()->pluck('pivot.user_id'); // just logics

//        $brandUsers = User::query()->whereIn('id', $userIds)->get();


//        $users = unique($users and $brandusers)

//        DB::connection($this->db)->insert($users);

        // такая же логика по другим таблицам 
    }
}
