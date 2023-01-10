<?php

declare(strict_types=1);

namespace Modules\Transaction\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

final class CurrencyConverter
{
    private const EXCHANGE_RATES_API_URL = 'https://api.exchangerate.host';

    public function convert(string $from, string $to, ?float $amount = null): float
    {
        if (!($from && $to)) {
            throw new RuntimeException(__("'from' and 'to' arguments must not be empty"));
        }

        $response = Http::get(
            self::EXCHANGE_RATES_API_URL . '/convert',
            compact('from', 'to', 'amount')
        );

        if (!$response->ok() || !($result = $response->json('result'))) {
            throw new RuntimeException(__("An error occurred while getting information about the exchange rate"));
        }

        return $result;
    }
}
