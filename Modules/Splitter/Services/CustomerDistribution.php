<?php

declare(strict_types=1);

namespace Modules\Splitter\Services;

use Carbon\Carbon;
use Modules\Customer\Enums\Gender;
use Modules\Customer\Models\Customer;
use Modules\Department\Enums\DepartmentEnum;
use Modules\Department\Models\Department;
use Modules\Splitter\Enums\SplitterChoiceOptionPerDay;
use Modules\Splitter\Enums\SplitterChoiceType;
use Modules\Splitter\Enums\SplitterConditions;
use Spatie\Multitenancy\Models\Tenant;

final class CustomerDistribution
{
    protected Tenant $currentBrand;

    public function run(Customer $customer, Tenant $currentBrand) : void
    {
        $splitters = $currentBrand->splitters()
            ->with('splitterChoice', 'splitterChoice.desks', 'splitterChoice.workers')
            ->whereIsActive(true)
            ->orderBy('position')
            ->get();

        foreach ($splitters as $splitter) {

            if (count($splitter->conditions) > 0) {
                if ($this->checkConditions($customer, $splitter))
                    $this->checkFieldAndUpdate($customer, $this->splitterChoice($splitter));
            } else {
                $this->checkFieldAndUpdate($customer, $this->splitterChoice($splitter));
            }

            $customer->refresh();
        }
    }

    private function checkFieldAndUpdate(Customer $customer, array $updateData)
    {
        if ($updateData['value'] > 0) {
            $customer->{$updateData['field']} = $updateData['value'];
            $customer->save();
        }
    }

    private function splitterChoice($splitter)
    {
        if ($splitter->splitterChoice->type == SplitterChoiceType::DESK) {
            return [
                'field' => 'desk_id',
                'value' => $this->splitterDesks($splitter)
            ];
        } elseif ($splitter->splitterChoice->type == SplitterChoiceType::WORKER) {
            return $this->splitterWorkers($splitter);
        }
    }

    private function splitterWorkers($splitter)
    {
        $workerIds = $splitter->splitterChoice->workers()->whereIsActive(true)->with('roles')->get();

        $getWorkersStats = [
            'conversion_user_id' => $this->getCustomersStats('conversion_user_id', $workerIds->pluck('id')),
            'retention_user_id' => $this->getCustomersStats('retention_user_id', $workerIds->pluck('id')),
            'conversion_manager_user_id' => $this->getCustomersStats('conversion_manager_user_id', $workerIds->pluck('id')),
            'retention_manager_user_id' => $this->getCustomersStats('retention_manager_user_id', $workerIds->pluck('id')),
        ];

        print_r($getWorkersStats);

        if ($splitter->splitterChoice->option_per_day == SplitterChoiceOptionPerDay::PERCENT_PER_DAY) {
        } elseif ($splitter->splitterChoice->option_per_day == SplitterChoiceOptionPerDay::CAPACITY_PER_DAY) {
        }
    }

    private function splitterDesks($splitter)
    {
        $deskIds = $splitter->splitterChoice->desks()->whereIsActive(true)->get();

        $getDesksStats = $this->getCustomersStats('desk_id', $deskIds->pluck('id'));

        $zeroDesks = $getDesksStats->where('customers', 0);

        if ($splitter->splitterChoice->option_per_day == SplitterChoiceOptionPerDay::PERCENT_PER_DAY) {

            if (count($zeroDesks) > 0) {
                $newDeskId = $zeroDesks->random()['id'];
            } else {
                $newDeskId = 0;

                $deskIds->pluck('pivot.percentage_per_day', 'id')
                    ->each(function ($percentage, $deskId) use (&$newDeskId, $getDesksStats) {

                        if ($deskStat = $getDesksStats->firstWhere('id', $deskId)) {

                            if ($percentage > $deskStat['percentage']) {
                                $newDeskId = $deskId;
                                return false;
                            }
                        }
                    });
            }

            return $newDeskId;
        } elseif ($splitter->splitterChoice->option_per_day == SplitterChoiceOptionPerDay::CAPACITY_PER_DAY) {

            if (count($zeroDesks) > 0) {
                $newDeskId = $zeroDesks->random()['id'];
            } else {
                $newDeskId = 0;

                $deskIds->pluck('pivot.cap_per_day', 'id')
                    ->each(function ($cap_per_day, $deskId) use (&$newDeskId, $getDesksStats) {
                        if ($deskStat = $getDesksStats->firstWhere('id', $deskId)) {

                            if ($cap_per_day > $deskStat['customers']) {
                                $newDeskId = $deskId;
                                return false;
                            }
                        }
                    });
            }

            return $newDeskId;
        }
    }

    private function getCustomersStats(string $field, object $ids): object
    {
        $customers = Customer::get()
            ->whereIn($field, $ids)
            ->where('created_at', '>=', Carbon::today())
            ->groupBy($field)
            ->map(function ($entries) {
                return $entries->count();
            });

        return $ids->map(function ($id) use ($customers, $field) {
            return [
                'id' => $id,
                'field' => $field,
                'customers' => $customers[$id] ?? 0,
                'percentage' => isset($customers[$id]) ? $customers[$id] / $customers->sum() * 100 : 0
            ];
        });
    }

    private function checkConditions(Customer $customer, $splitter): bool
    {
        /*
        -Country IN, NOT IN                 country_id
        -Is FTD true/false                  is_ftd
        -Language IN, NOT IN                language_id
        -Campaign IN, NOT IN                campaign_id
        -Conversion Agent IN, NOT IN        conversion_user_id
        -Conversion Manager IN, NOT IN      conversion_manager_user_id
        -Desk IN, NOT IN                    desk_id

        -Gender male, female                gender
        -Department Conversion/Retention    department_id
        */
        foreach ($splitter->conditions as $condition) {
            if ($dbField = collect(SplitterConditions::dbFields())->get($condition['field'])) {
                $customer = match ($condition['operator']) {
                    'IN' => $customer->whereIn($dbField, $condition['value']),
                    'NOT IN' => $customer->whereNotIn($dbField, $condition['value']),
                    '=' => $customer->where($dbField, $condition['value']),
                };
            } elseif ($condition['field'] == SplitterConditions::GENDER) {
                $customer = $customer->where('gender', Gender::fromKey(strtoupper($condition['value']))->value);
            } elseif ($condition['field'] == SplitterConditions::DEPARTMENT) {
                $customer = $customer->where(
                    'department_id',
                    Department::whereName(DepartmentEnum::fromKey(strtoupper($condition['value']))->value)->first()->id
                );
            }
        }

        return $customer->exists();
    }
}
