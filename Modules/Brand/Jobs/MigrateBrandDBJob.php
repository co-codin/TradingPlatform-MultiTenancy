<?php
declare(strict_types=1);

namespace Modules\Brand\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;

class MigrateBrandDBJob implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use InteractsWithQueue;
    use SerializesModels;

    protected array $tables;

    public function __construct(array $tables)
    {
    }

    public function handle(): void
    {
        putenv('');
        foreach ($this->tables as $table) {
            Artisan::call(sprintf(
                'migrate --database=%s --path=%s'
            ));
        }
    }
}
