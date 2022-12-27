<?php

declare(strict_types=1);

namespace Modules\User\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Modules\User\Models\User;

final class WorkerExport implements FromCollection
{
    /**
     * @var string
     */
    public const EXPORT_FILE_NAME = 'workers';

    /**
     * @var Collection $collection
     */
    private Collection $collection;

    /**
     * {@inheritDoc}
     */
    public function collection(): Collection
    {
        return $this->collection ?? User::query()->get();
    }

    /**
     * Set collection.
     *
     * @param Collection $collection
     * @return FromCollection
     */
    public function setCollection(Collection $collection): FromCollection
    {
        $this->collection = $collection;

        return $this;
    }
}
