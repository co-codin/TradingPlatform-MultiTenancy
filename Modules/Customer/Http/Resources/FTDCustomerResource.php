<?php

declare(strict_types=1);

namespace Modules\Customer\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Customer\Models\Customer;

final class FTDCustomerResource extends JsonResource
{
    /**
     * {@inheritDoc}
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
