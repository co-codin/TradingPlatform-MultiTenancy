<?php

declare(strict_types=1);

namespace App\Contracts;

interface HasTenantDBConnection
{
    /**
     * Get tenant connection data.
     *
     * @return array
     */
    public function getTenantConnectionData(): array;

    /**
     * Get tenant schema name.
     *
     * @return string
     */
    public function getTenantSchemaName(): string;
}
