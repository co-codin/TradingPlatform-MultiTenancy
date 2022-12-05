<?php

declare(strict_types=1);

namespace Modules\Customer\Exports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Modules\Customer\Models\Customer;

final class CustomerExport implements FromCollection
{
    /**
     * @var string
     */
    public const EXPORT_FILE_NAME = 'customers';

    /**
     * @var Collection $collection
     */
    private Collection $collection;

    /**
     * {@inheritDoc}
     */
    public function collection(): Collection
    {
        return $this->collection ?? Customer::query()->get();
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
