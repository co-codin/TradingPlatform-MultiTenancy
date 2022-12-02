<?php

declare(strict_types=1);

namespace Modules\Customer\Imports;

use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Modules\Customer\Dto\CustomerDto;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

final class CustomerImport implements ToArray, WithHeadingRow
{
    /**
     * @var string
     */
    public const IMPORT_FILE_NAME = 'customers';

    /**
     * @param array $array
     * @return array
     * @throws UnknownProperties
     */
    public function array(array $array): array
    {
        return (new CustomerDto($array))->toArray();
    }
}
