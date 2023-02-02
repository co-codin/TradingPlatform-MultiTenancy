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
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected Tenant $currentBrand;

    public function run(Customer $customer, Tenant $currentBrand): void
    {
        $splitters = $currentBrand->splitters()
            ->with('splitterChoice', 'splitterChoice.desks', 'splitterChoice.workers')
            ->whereIsActive(true)
            ->orderBy('position')
            ->get();

        foreach ($splitters as $splitter) {
            if (count($splitter->conditions) > 0) {
                if ($this->checkConditions($customer, $splitter)) {
                    $this->checkFieldAndUpdate($customer, $this->splitterChoice($splitter));
                }
            } else {
                $this->checkFieldAndUpdate($customer, $this->splitterChoice($splitter));
            }

            $customer->refresh();
        }
    }

    private function checkFieldAndUpdate(Customer $customer, array $updateData): void
    {
        if ($updateData['value'] > 0) {
            $customer->{$updateData['field']} = $updateData['value'];
            $customer->save();
        }
    }

    private function splitterChoice(object $splitter): array
    {
        if ($splitter->splitterChoice->type == SplitterChoiceType::DESK) {
            return $this->splitterReturn('desk_id', $this->splitterDesks($splitter));
        } elseif ($splitter->splitterChoice->type == SplitterChoiceType::WORKER) {
            return $this->splitterWorkers($splitter);
        }

        return $this->splitterReturn();
    }

    private function splitterWorkers(object $splitter): array
    {
        $workerIds = $splitter->splitterChoice->workers()->whereIsActive(true)->with('roles')->get();

        $mergedWorkerStats = $this->getMergedWorkerStats($workerIds);

        $zeroWorkers = $mergedWorkerStats->where('customers', 0);

        if ($splitter->splitterChoice->option_per_day == SplitterChoiceOptionPerDay::PERCENT_PER_DAY) {
            if (count($zeroWorkers) > 0) {
                $randomZeroWorker = $zeroWorkers->random();
                $newWorkerData = $this->splitterReturn($randomZeroWorker['field'], $randomZeroWorker['id']);
            } else {
                $newWorkerData = $this->splitterReturn();

                $workerIds->pluck('pivot.percentage_per_day', 'id')
                    ->each(function ($percentage, $workerId) use (&$newWorkerData, $mergedWorkerStats) {
                        if ($workerStat = $mergedWorkerStats->firstWhere('id', $workerId)) {
                            if ($percentage > $workerStat['percentage']) {
                                $newWorkerData = $this->splitterReturn($workerStat['field'], $workerStat['id']);

                                return false;
                            }
                        }
                    });
            }

            return $newWorkerData;
        } elseif ($splitter->splitterChoice->option_per_day == SplitterChoiceOptionPerDay::CAPACITY_PER_DAY) {
            if (count($zeroWorkers) > 0) {
                $randomZeroWorker = $zeroWorkers->random();
                $newWorkerData = $this->splitterReturn($randomZeroWorker['field'], $randomZeroWorker['id']);
            } else {
                $newWorkerData = $this->splitterReturn();

                $workerIds->pluck('pivot.cap_per_day', 'id')
                    ->each(function ($cap_per_day, $workerId) use (&$newWorkerData, $mergedWorkerStats) {
                        if ($workerStat = $mergedWorkerStats->firstWhere('id', $workerId)) {
                            if ($cap_per_day > $workerStat['customers']) {
                                $newWorkerData = $this->splitterReturn($workerStat['field'], $workerStat['id']);

                                return false;
                            }
                        }
                    });
            }

            return $newWorkerData;
        }

        return $this->splitterReturn();
    }

    private function splitterReturn(string $field = '', int $value = 0): array
    {
        return [
            'field' => $field,
            'value' => $value,
        ];
    }

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

    private function splitterDesks(object $splitter): int
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

        return 0;
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
                'percentage' => isset($customers[$id]) ? $customers[$id] / $customers->sum() * 100 : 0,
            ];
        });
    }

    private function checkConditions(Customer $customer, $splitter): bool
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
