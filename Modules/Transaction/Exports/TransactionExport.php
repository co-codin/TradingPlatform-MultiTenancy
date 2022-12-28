<?php

declare(strict_types=1);

namespace Modules\Transaction\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Modules\Transaction\Models\Transaction;

final class TransactionExport implements FromCollection
{
    /**
     * @var string
     */
    public const EXPORT_FILE_NAME = 'transactions';

    /**
     * @var Collection $collection
     */
    private Collection $collection;

    /**
     * {@inheritDoc}
     */
    public function collection(): Collection
    {
        return $this->collection ?? Transaction::query()->get();
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
