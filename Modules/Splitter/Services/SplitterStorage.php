<?php

declare(strict_types=1);

namespace Modules\Splitter\Services;

use Exception;
use Modules\Splitter\Dto\SplitterDto;
use Modules\Splitter\Enums\SplitterChoiceOptionPerDay;
use Modules\Splitter\Enums\SplitterChoiceType;
use Modules\Splitter\Models\Splitter;
use Modules\Splitter\Models\SplitterChoice;
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
            $splitter->splitterChoice()->updateOrCreate($splitterDto->splitter_choice);
        }

        $this->reposition($splitter->user_id);

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
    public function updatePositions(User $user, array $splitterids): bool
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
                'cap_per_day' => $item['cap_per_day'],
                'percentage_per_day' => $item['percentage_per_day'],
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

        return $splitterChoice->refresh();
    }
}
