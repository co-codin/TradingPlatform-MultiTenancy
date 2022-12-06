<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection as SupportCollection;
use Symfony\Component\HttpFoundation\Response;

class BaseFormRequest extends FormRequest
{
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
     * Prefix for rules.
     *
     * @param  array  $rules
     * @param  string  $prefix
     * @return SupportCollection
     */
    final protected function prefix(array $rules, string $prefix = ''): SupportCollection
    {
        return collect($rules)->map(fn (string $rule) => "{$prefix}.{$rule}");
    }

    /**
     * {@inheritDoc}
     */
    protected function passedValidation(): void
    {
        abort_if(! empty($this->rules()) && ! $this->validated(), Response::HTTP_BAD_REQUEST);
    }
}
