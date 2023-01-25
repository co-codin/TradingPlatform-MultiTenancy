<?php

declare(strict_types=1);

namespace Modules\Splitter\Services;

use Exception;
use Modules\Splitter\Dto\SplitterDto;
use Modules\Splitter\Models\Splitter;
use Modules\User\Models\User;

final class SplitterStorage
{
    /**
     * Store.
     *
     * @param  User  $user
     * @param  SplitterDto  $splitterDto
     * @return Splitter
     *
     * @throws Exception
     */
    public function store(User $user, SplitterDto $splitterDto): Splitter
    {
        $createData = $splitterDto->toArray();
        $createData['user_id'] = $user->id;

        if ($splitterDto->is_active) {
            $createData['position'] = Splitter::whereUserId($user->id)->max('position') + 1;
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

        $this->reposition($splitter->user_id);

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

        $this->reposition($splitter->user_id);

        return true;
    }

    /**
     * Update Positions.
     *
     * @param  User  $user
     * @param  array  $splitterids
     * @return bool
     */
    public function updatePositions(User $user, array $splitterids) //: bool
    {
        $postition = 1;
        if (
            collect($splitterids)->each(function ($id) use (&$postition) {
                Splitter::whereIsActive(true)->find($id)?->update([
                    'position' => $postition++,
                ]);
            })->count() < Splitter::whereIsActive(true)->whereUserId($user->id)->count()
        ) {
            $this->reposition($user->id);
        }

        return true;
    }

    /**
     * Reposition.
     *
     * @param  int  $id
     * @return void
     */
    private function reposition(int $id): void
    {
        $splitters = Splitter::withTrashed()->whereUserId($id)
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
