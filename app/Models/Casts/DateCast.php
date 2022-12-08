<?php

declare(strict_types=1);

namespace App\Models\Casts;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

final class DateCast implements CastsAttributes
{
    /**
     * @var string
     */
    public const FORMAT = 'Y-d-m H:i:s';

    /**
     * {@inheritDoc}
     */
    public function get($model, string $key, $value, array $attributes)
    {
        return $this->initDate($value)->format(self::FORMAT);
    }

    /**
     * {@inheritDoc}
     */
    public function set($model, string $key, $value, array $attributes)
    {
        return $this->initDate($value)->format(self::FORMAT);
    }

    /**
     * Init date.
     *
     * @param $value
     * @return Carbon
     */
    private function initDate($value): Carbon
    {
        return match (true) {
            is_subclass_of($value, Carbon::class) => $value,
            default => Carbon::create($value),
        };
    }
}
