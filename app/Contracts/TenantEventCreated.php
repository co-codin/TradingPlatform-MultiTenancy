<?php

namespace App\Contracts;

interface TenantEventCreated
{
    /**
     * Get tenant db name.
     *
     * @return string
     */
    public function getTenantDBName(): string;
}
