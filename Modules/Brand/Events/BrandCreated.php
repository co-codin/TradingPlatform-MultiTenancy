<?php

declare(strict_types=1);

namespace Modules\Brand\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Brand\Models\Brand;

final class BrandCreated
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    final public function __construct(public readonly Brand $brand)
    {}
}
