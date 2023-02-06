<?php

declare(strict_types=1);

namespace Modules\Splitter\Services;

use Exception;
use Modules\Splitter\Dto\SplitterDto;
use Modules\Splitter\Enums\SplitterChoiceOptionPerDay;
use Modules\Splitter\Enums\SplitterChoiceType;
use Modules\Splitter\Models\Splitter;
use Modules\Splitter\Models\SplitterChoice;
use Spatie\Multitenancy\Models\Tenant;

final class SplitterStorage
{
    /**
     * Store.
     *
     * @param  SplitterDto  $splitterDto
     * @return Splitter
     *
     * @throws Exception
     */
    public function store(SplitterDto $splitterDto): Splitter
    {
        if (! $brand_id = Tenant::current()?->id) {
            throw new Exception(__('Can not store splitter without brand'));
        }

        $createData = $splitterDto->toArray();
        $createData['brand_id'] = $brand_id;

        if ($splitterDto->is_active) {
            $createData['position'] = Splitter::whereBrandId($brand_id)->max('position') + 1;
        }

        if (! $splitter = Splitter::query()->create($createData)) {
            throw new Exception(__('Can not store splitter'));
        }

        if ($splitterDto->splitter_choice) {
            $splitter->splitterChoice()->create($splitterDto->splitter_choice);
        }

        return $splitter->fresh()->load('splitterChoice');
    }

    /**
     * Update.
     *
     * @param  Splitter  $splitter
     * @param  SplitterDto  $splitterDto
     * @return Splitter
     *
     * @throws Exception
     */
    public function update(Splitter $splitter, SplitterDto $splitterDto): Splitter
    {
        if (! $splitter->update($splitterDto->toArray())) {
            throw new Exception(__('Can not update splitter'));
        }

        if ($splitterDto->splitter_choice) {
            $splitter->splitterChoice()->updateOrCreate(
                [],
                $splitterDto->splitter_choice
            );
        }

        $this->reposition();

        return $splitter->fresh()->load('splitterChoice');
    }

    /**
     * Delete.
     *
     * @param  Splitter  $splitter
     * @return bool
     */
    public function delete(Splitter $splitter): bool
    {
        if (! $splitter->delete()) {
            throw new Exception(__('Can not delete splitter'));
        }

        $splitter->splitterChoice()->delete();

        $this->reposition();

        return true;
    }

    /**
     * Update Positions.
     *
     * @param  array  $splitterids
     * @return bool
     */
    public function updatePositions(array $splitterids): bool
    {
        if (! $brand_id = Tenant::current()?->id) {
            throw new Exception(__('Can not update positions without brand'));
        }

        $postition = 1;
        if (
            collect($splitterids)->each(function ($id) use (&$postition) {
                Splitter::whereIsActive(true)->find($id)?->update([
                    'position' => $postition++,
                ]);
            })->count() < Splitter::whereIsActive(true)->whereBrandId($brand_id)->count()
        ) {
            $this->reposition();
        }

        return true;
    }

    /**
     * Reposition.
     *
     * @return void
     */
    private function reposition(): void
    {
        $brand_id = Tenant::current()?->id;

        $splitters = Splitter::withTrashed()->whereBrandId($brand_id)
            ->orderBy('is_active', 'DESC')
            ->orderBy('position', 'ASC')
            ->orderBy('updated_at', 'DESC')
            ->get();
        $newPosition = 1;
        foreach ($splitters as $splitter) {
            if ($splitter->is_active && $splitter->deleted_at == null) {
                $splitter->position = $newPosition;
                $newPosition++;
            } else {
                $splitter->position = null;
            }

            $splitter->save();
        }
    }

    /**
     * Splitter Choice Put.
     *
     * @param  SplitterChoice  $splitterChoice
     * @param  array  $ids
     * @return SplitterChoice
     *
     * @throws Exception
     */
    public function put(SplitterChoice $splitterChoice, array $ids): SplitterChoice
    {
        $percentage = $splitterChoice->option_per_day == SplitterChoiceOptionPerDay::PERCENT_PER_DAY ? 100 : 0;

        $idList = [];
        collect($ids)->each(function ($item) use (&$idList, $percentage) {
            $idList[$item['id']] = [
                'percentage' => $percentage,
                'cap_per_day' => $item['cap_per_day'] ?? 0,
                'percentage_per_day' => $item['percentage_per_day'] ?? 0,
            ];
        });

        if ($splitterChoice->type == SplitterChoiceType::DESK) {
            if (! $splitterChoice->desks()->sync($idList)) {
                throw new Exception(__('Can not update desks for splitter choice'));
            }
        } else {
            if (! $splitterChoice->workers()->sync($idList)) {
                throw new Exception(__('Can not update workers for splitter choice'));
            }
        }

        activity()
            ->performedOn($splitterChoice)
            ->withProperties($idList)
            ->event('sync')
            ->causedBy(auth()->user())
            ->log('sync');

        return $splitterChoice->refresh();
    }
}
