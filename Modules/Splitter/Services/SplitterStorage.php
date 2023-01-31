<?php

declare(strict_types=1);

namespace Modules\Splitter\Services;

use Exception;
use Modules\Splitter\Dto\SplitterDto;
use Modules\Splitter\Models\Splitter;
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

        return $splitter->fresh();
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

        $this->reposition();

        return $splitter->fresh();
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
}
