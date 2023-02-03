<?php

declare(strict_types=1);

namespace Modules\Splitter\Services;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Modules\Customer\Enums\Gender;
use Modules\Customer\Models\Customer;
use Modules\Department\Enums\DepartmentEnum;
use Modules\Department\Models\Department;
use Modules\Splitter\Enums\SplitterChoiceOptionPerDay;
use Modules\Splitter\Enums\SplitterChoiceType;
use Modules\Splitter\Enums\SplitterConditions;
use Spatie\Multitenancy\Models\Tenant;

final class CustomerDistribution implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Run Customer distribution
     *
     * @param  Customer  $customer
     * @param  Tenant  $currentBrand
     * @return void
     */
    public function run(Customer $customer, Tenant $currentBrand): void
    {
        $splitters = $this->getBrandSplitters($currentBrand);

        foreach ($splitters as $splitter) {
            if ($splitter->conditions && count($splitter->conditions) > 0) {
                if ($this->checkConditions($customer, $splitter)) {
                    $this->checkFieldAndUpdate($customer, $this->splitterChoice($splitter));
                }
            } else {
                $this->checkFieldAndUpdate($customer, $this->splitterChoice($splitter));
            }

            $customer->refresh();
        }
    }

    /**
     * Get current brand splitters
     *
     * @param  Tenant  $currentBrand
     * @return object
     */
    private function getBrandSplitters(Tenant $currentBrand): object
    {
        return $currentBrand->splitters()
            ->with('splitterChoice', 'splitterChoice.desks', 'splitterChoice.workers')
            ->whereIsActive(true)
            ->orderBy('position')
            ->get();
    }

    /**
     * Update customer desk or worker id in selected field
     *
     * @param  Customer  $customer
     * @param  array  $updateData
     * @return void
     */
    private function checkFieldAndUpdate(Customer $customer, array $updateData): void
    {
        if ($updateData['value'] > 0) {
            $customer->{$updateData['field']} = $updateData['value'];
            $customer->save();
        }
    }

    /**
     * Checking the splitter type and run the appropriate method (splitterDesks or splitterWorkers)
     *
     * @param  object  $splitter
     * @return array
     */
    private function splitterChoice(object $splitter): array
    {
        if ($splitter->splitterChoice?->type == SplitterChoiceType::DESK) {
            return $this->splitterReturn('desk_id', $this->splitterDesks($splitter));
        } elseif ($splitter->splitterChoice?->type == SplitterChoiceType::WORKER) {
            return $this->splitterWorkers($splitter);
        }

        return $this->splitterReturn();
    }

    /**
     * 1. Checking if there are zero workers then define random worker and return
     * 2. Checking if there are no zero workers then define worker by the PERCENT_PER_DAY or CAPACITY_PER_DAY and return
     *
     * @param  object  $splitter
     * @return array
     */
    private function splitterWorkers(object $splitter): array
    {
        $workerIds = $splitter->splitterChoice->workers()->whereIsActive(true)->with('roles')->get();

        $mergedWorkerStats = $this->getMergedWorkerStats($workerIds);

        $zeroWorkers = $mergedWorkerStats->where('customers', 0);

        if (count($zeroWorkers) > 0) {
            $randomZeroWorker = $zeroWorkers->random();

            return $this->splitterReturn($randomZeroWorker['field'], $randomZeroWorker['id']);
        } else {
            if ($splitter->splitterChoice->option_per_day == SplitterChoiceOptionPerDay::PERCENT_PER_DAY) {
                return $this->splitterWorkerDefinition($workerIds, $mergedWorkerStats, 'pivot.percentage_per_day', 'percentage');
            } elseif ($splitter->splitterChoice->option_per_day == SplitterChoiceOptionPerDay::CAPACITY_PER_DAY) {
                return $this->splitterWorkerDefinition($workerIds, $mergedWorkerStats, 'pivot.cap_per_day', 'customers');
            }
        }

        return $this->splitterReturn();
    }

    /**
     * Worker definition by worker statistics
     *
     * @param  object  $workerIds
     * @param  object  $mergedWorkerStats
     * @param  string  $pivodField
     * @param  string  $workerStatBy
     * @return array
     */
    private function splitterWorkerDefinition(object $workerIds, object $mergedWorkerStats, string $pivodField, string $workerStatBy): array
    {
        $newWorkerData = $this->splitterReturn();

        $workerIds->pluck($pivodField, 'id')
            ->each(function ($fieldValue, $workerId) use (&$newWorkerData, $mergedWorkerStats, $workerStatBy) {
                if ($workerStat = $mergedWorkerStats->firstWhere('id', $workerId)) {
                    if ($fieldValue > $workerStat[$workerStatBy]) {
                        $newWorkerData = $this->splitterReturn($workerStat['field'], $workerStat['id']);

                        return false;
                    }
                }
            });

        return $newWorkerData;
    }

    /**
     * Splitter return tempalte for update customer
     *
     * @param  string  $field
     * @param  int  $value
     * @return array
     */
    private function splitterReturn(string $field = '', int $value = 0): array
    {
        return [
            'field' => $field,
            'value' => $value,
        ];
    }

    /**
     * Collecting and merge worker stat by fields:
     *  - conversion_user_id
     *  - retention_user_id
     *  - conversion_manager_user_id
     *  - retention_manager_user_id
     *
     * @param  object  $workerIds
     * @return object
     */
    private function getMergedWorkerStats(object $workerIds): object
    {
        $getWorkersStats = $this->getCustomersStats('conversion_user_id', $workerIds->pluck('id'))
            ->merge($this->getCustomersStats('retention_user_id', $workerIds->pluck('id')))
            ->merge($this->getCustomersStats('conversion_manager_user_id', $workerIds->pluck('id')))
            ->merge($this->getCustomersStats('retention_manager_user_id', $workerIds->pluck('id')));

        return $getWorkersStats->map(function ($entries) use ($getWorkersStats) {
            return [
                'id' => $entries['id'],
                'field' => $entries['field'],
                'customers' => $entries['customers'],
                'percentage' => $entries['customers'] ? $entries['customers'] / $getWorkersStats->sum('customers') * 100 : 0,
            ];
        });
    }

    /**
     * Desk definition by desk statistics
     *
     * @param  object  $deskIds
     * @param  object  $getDesksStats
     * @param  string  $pivodField
     * @param  string  $workerStatBy
     * @return int
     */
    private function splitterDeskDefinition(object $deskIds, object $getDesksStats, string $pivodField, string $workerStatBy): int
    {
        $newDeskId = 0;

        $deskIds->pluck($pivodField, 'id')
            ->each(function ($fieldValue, $deskId) use (&$newDeskId, $getDesksStats, $workerStatBy) {
                if ($deskStat = $getDesksStats->firstWhere('id', $deskId)) {
                    if ($fieldValue > $deskStat[$workerStatBy]) {
                        $newDeskId = $deskId;

                        return false;
                    }
                }
            });

        return $newDeskId;
    }

    /**
     * 1. Checking if there are zero desks then define random desk and return
     * 2. Checking if there are no zero desks then define desk by the PERCENT_PER_DAY or CAPACITY_PER_DAY and return
     *
     * @param  object  $splitter
     * @return int
     */
    private function splitterDesks(object $splitter): int
    {
        $deskIds = $splitter->splitterChoice->desks()->whereIsActive(true)->get();

        $getDesksStats = $this->getCustomersStats('desk_id', $deskIds->pluck('id'));

        $zeroDesks = $getDesksStats->where('customers', 0);

        if (count($zeroDesks) > 0) {
            return $zeroDesks->random()['id'];
        } else {
            if ($splitter->splitterChoice->option_per_day == SplitterChoiceOptionPerDay::PERCENT_PER_DAY) {
                return $this->splitterDeskDefinition($deskIds, $getDesksStats, 'pivot.percentage_per_day', 'percentage');
            } elseif ($splitter->splitterChoice->option_per_day == SplitterChoiceOptionPerDay::CAPACITY_PER_DAY) {
                return $this->splitterDeskDefinition($deskIds, $getDesksStats, 'pivot.cap_per_day', 'customers');
            }
        }

        return 0;
    }

    /**
     * Grouped customer by:
     *  - Desk: desk_id
     *  - Worker: conversion_user_id, retention_user_id, conversion_manager_user_id, retention_manager_user_id
     *
     * @param  string  $field
     * @param  object  $ids
     * @return object
     */
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
                'percentage' => isset($customers[$id]) ? $customers[$id] / $customers->sum() * 100 : 0,
            ];
        });
    }

    /**
     * Check customer by splitter conditions and return true or false
     *
     * @param  Customer  $customer
     * @param  object  $splitter
     * @return bool
     */
    private function checkConditions(Customer $customer, object $splitter): bool
    {
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
