<?php

declare(strict_types=1);

namespace Modules\Customer\Dto;

use App\Dto\BaseDto;

final class UrlAuthDto extends BaseDto
{
    public readonly int $customerId;
    public readonly int $brandId;
}
