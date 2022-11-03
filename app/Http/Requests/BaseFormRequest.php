<?php


namespace App\Http\Requests;


use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Collection as SupportCollection;
use Symfony\Component\HttpFoundation\Response;

class BaseFormRequest extends FormRequest
{
    protected function prefix(array $rules, string $prefix = ''): SupportCollection
    {
        return collect($rules)->map(fn(string $rule) => $prefix . '.' . $rule);
    }

    protected function passedValidation(): void
    {
        abort_if(!$this->validated(), Response::HTTP_BAD_REQUEST);
    }
}
