<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Modules\Campaign\Models\Campaign;
use Modules\Campaign\Services\BalanceCalculation;

class campaignsBalanceCalculation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'campaigns:balance-calculation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Campaigns balance calculation';

    /**
     * @param  BalanceCalculation  $balanceCalculation
     */
    public function __construct(
        protected BalanceCalculation $balanceCalculation,
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        set_time_limit(0);

        $this->balanceCalculation->runForAllCampaign();

        // return Command::SUCCESS;
    }
}
