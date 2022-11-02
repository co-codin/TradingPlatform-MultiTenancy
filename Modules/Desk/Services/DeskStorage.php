<?php


namespace Modules\Desk\Services;


use Modules\Desk\Dto\DeskDto;
use Modules\Desk\Models\Desk;

class DeskStorage
{
    public function store(DeskDto $deskDto)
    {
        return  Desk::query()->create($deskDto->toArray());
    }

    public function update(Desk $desk, DeskDto $deskDto)
    {
        $attributes = $deskDto->toArray();

        if (!$desk->update($attributes)) {
            throw new \LogicException('can not update desk');
        }

        return $desk;
    }

    public function delete(Desk $desk)
    {
        if (!$desk->delete()) {
            throw new \LogicException('can not delete desk');
        }
    }
}
