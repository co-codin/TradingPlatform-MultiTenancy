<?php

declare(strict_types=1);

namespace Modules\Campaign\Services;

use Carbon\Carbon;
use Illuminate\Support\Arr;
use Modules\Campaign\Enums\CampaignTransactionType;
use Modules\Campaign\Models\Campaign;
use Modules\Campaign\Models\CampaignTransaction;
use Modules\User\Models\User;

class BalanceCalculation
{
    /**
     * @param  Campaign  $campaign
     */
    public function __construct(
        protected Campaign $campaign,
    ) {
    }

    /**
     * Run for all campaigns
     *
     * @return bool
     */
    public function runForAllCampaign(): bool
    {
        $campaigns = $this->campaign->get();
        foreach ($campaigns as $campaign) {
            if ($brand = User::findOrFail($campaign->affiliate_id)->brands()->first()) {
                $brand->makeCurrent();

                $this->run($campaign);
            }
        }

        return true;
    }

    /**
     * Run for campaign
     *
     * @param  int $campaignId
     * @return array
     */
    public function runForCampaign(int $campaignId): array
    {
        $campaign = $this->campaign->findOrFail($campaignId);

        return $this->run($campaign);
    }

    /**
     * View
     *
     * @param  int $campaignId
     * @return array
     */
    public function view(int $campaignId): array
    {
        $campaign = $this->campaign->findOrFail($campaignId);

        return $this->calculate($campaign);
    }

    /**
     * Run calculate
     *
     * @param  Campaign $campaign
     * @return array
     */
    private function run(Campaign $campaign): array
    {
        $campaignBalance = $this->calculate($campaign);

        /** Update campaign balance */
        $campaign->balance += $campaignBalance['balance']['total'];
        $campaign->save();

        /** Create campaign transaction */
        if ($campaignBalance['balance']['total']) {
            CampaignTransaction::create([
                'affiliate_id' => $campaignBalance['affiliate_id'],
                'type' => CampaignTransactionType::PAYMENT,
                'amount' => $campaignBalance['balance']['total'],
                'customer_ids' => $campaignBalance['customerIds']
            ]);
        }

        return $campaignBalance;
    }
    /**
     * Calculate
     *
     * @param  Campaign $campaign
     * @return array
     */
    private function calculate(Campaign $campaign): array
    {
        $totalCustomersByCountry = $this->getData($campaign);

        $byCountry = [];
        $totalBalance = 0;
        $customerIds = [];
        foreach ($totalCustomersByCountry as $countryId => $data) {
            if ($attributes = $campaign->countries()->find($countryId)) {
                $cpa = $attributes->pivot->cpa;
                $crg = $attributes->pivot->crg;
            } else {
                $cpa = $campaign->cpa;
                $crg = $campaign->crg;
            }

            if ($weeklyBalance = $this->weeklyBalance($data, $cpa, $crg)) {
                $byCountry[$data['country']] = $weeklyBalance;
                $totalBalance += $byCountry[$data['country']];
                $customerIds = array_merge($customerIds, $data['lastWeekftdCustomerIds'], $data['oldFtdCustomerIds']);
            }
        }
        return [
            'id' => $campaign->id,
            'name' => $campaign->name,
            'affiliate_id' => $campaign->affiliate_id,
            'balance' => [
                'total' => $totalBalance,
                'byCountry' => $byCountry
            ],
            'customerIds' => $customerIds
        ];
    }

    /**
     * getData
     *
     * @param  mixed $campaign
     * @return array
     */
    private function getData($campaign): array
    {
        $campaignTransaction = CampaignTransaction::whereAffiliateId($campaign->affiliate_id)
            ->orderByDesc('id')
            ->get()
            ->map(function ($entries) {
                return $entries->customer_ids;
            })->toArray();

        $customerIds = Arr::collapse($campaignTransaction);

        return $campaign->customers()
            ->whereNotIn('id', $customerIds)
            ->get()
            ->groupBy('country_id', 'is_ftd')
            ->map(function ($entries) {
                $lastWeek = $entries->whereBetween('created_at', [Carbon::now()->subWeeks(1)->startOfWeek(), Carbon::now()->startOfWeek()]);
                return [
                    'country' => $entries->first()->country->iso2,
                    'lastWeekCustomers' => $lastWeek->count(),
                    'lastWeekftdCustomerIds' => $lastWeekftdCustomerIds = $lastWeek->where('is_ftd', true)->pluck('id')->toArray(),
                    'lastWeekftd' => count($lastWeekftdCustomerIds),
                    'oldFtdCustomerIds' => $oldFtdCustomerIds = $entries->where('created_at', '<', Carbon::now()->subWeeks(1)->startOfWeek())->where('is_ftd', true)->pluck('id')->toArray(),
                    'oldFtd' => count($oldFtdCustomerIds)
                ];
            })->toArray();
    }
    /**
     * getConversion
     *
     * @param  int $lastWeekCustomers
     * @param  int $totalFtd
     * @return float
     */
    private function getConversion(int $lastWeekCustomers, int $totalFtd): float
    {
        if ($lastWeekCustomers) {
            return 100 / $lastWeekCustomers * $totalFtd;
        }
        return 0;
    }
    /**
     * weeklyBalance
     *
     * @param  array $data
     * @param  float $cpa
     * @param  float $crg
     * @return float
     */
    private function weeklyBalance($data, $cpa, $crg): float
    {
        $ftds = $data['lastWeekftd'] + $data['oldFtd'];
        if ($ftds) {

            $customers = $data['lastWeekCustomers'];

            $cr = $this->getConversion($customers, $ftds);

            if ($cr >= $crg) {
                $weeklyBalance = $ftds * $cpa;
            } else {
                $weeklyBalance = $crg * $customers / 100 * $cpa;
            }

            return $weeklyBalance;
        }
        return 0;
    }
}
