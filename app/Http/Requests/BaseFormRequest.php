<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection as SupportCollection;
use Symfony\Component\HttpFoundation\Response;

class BaseFormRequest extends FormRequest
{
    /**
     * Prefix for rules.
     *
     * @param array $rules
     * @param string $prefix
     * @return SupportCollection
     */
    final protected function prefix(array $rules, string $prefix = ''): SupportCollection
    {
        return collect($rules)->map(fn(string $rule) => "{$prefix}.{$rule}");
    }

    /**
     * Rules.
     *
     * @return array
     */
    public function rules(): array
    {
        return [];
    }

    /**
     * {@inheritDoc}
     */
    final protected function passedValidation(): void
    {
        abort_if(! $this->validated(), Response::HTTP_BAD_REQUEST);
    }
}
