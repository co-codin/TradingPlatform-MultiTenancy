<?php

declare(strict_types=1);

namespace Modules\Splitter\Services;

use Exception;
use Modules\Splitter\Dto\SplitterDto;
use Modules\Splitter\Models\Splitter;

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
        if (!$splitter = Splitter::query()->create($splitterDto->toArray())) {
            throw new Exception(__('Can not store splitter'));
        }

        return $splitter;
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
        if (!$splitter->update($splitterDto->toArray())) {
            throw new Exception(__('Can not update splitter'));
        }

        return $splitter;
    }

    /**
     * Delete language.
     *
     * @param Splitter $splitter
     * @return bool
     */
    public function delete(Splitter $splitter): bool
    {
        if (!$splitter->delete()) {
            throw new Exception(__('Can not delete splitter'));
        }

        return true;
    }
}
