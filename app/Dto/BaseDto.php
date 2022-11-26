<?php

namespace App\Dto;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Http\FormRequest;
use ReflectionClass;
use ReflectionProperty;
use Spatie\DataTransferObject\Arr;
use Spatie\DataTransferObject\DataTransferObject;
use Spatie\DataTransferObject\Exceptions\UnknownProperties;

abstract class BaseDto extends DataTransferObject implements Arrayable
{
    protected array $visibleKeys = [];

    /**
     * @param FormRequest $request
     * @return static
     * @throws UnknownProperties
     */
    public static function fromFormRequest(FormRequest $request): static
    {
        $validated = $request->validated();

        return (new static($validated))
            ->visible(array_keys($validated));
    }

    /**
     * @param array $items
     * @return static
     * @throws UnknownProperties
     */
    public static function create(array $items): static
    {
        return new static($items);
    }

    public function visible(array $keys): static
    {
        $dataTransferObject = clone $this;

        $dataTransferObject->visibleKeys = [...$this->visibleKeys, ...$keys];

        return $dataTransferObject;
    }

    public function toArray(): array
    {
        $array = parent::toArray();

        return $this->visibleKeys ? Arr::only($array, $this->visibleKeys) : $array;
    }

    /**
     * @param ...$properties
     * @return $this
     * @throws \JsonException
     */
    public function toJsonProperties(...$properties): self
    {
        $dto = clone $this;

        foreach ($properties as $property) {
            $dto->{$property} = json_encode($this->{$property}, JSON_THROW_ON_ERROR);
        }

        return $dto;
    }

    /**
     * @param string ...$keys
     * @return $this
     * @throws \Exception
     */
    public function except(string ...$keys): static
    {
        $dto = clone $this;

        foreach ($keys as $key) {
            $properties = explode('.', $key);

            if (count($properties) > 1) {
                $descendant = $properties[0];
                unset($properties[0]);

                $dto->{$descendant} = match (true) {
                    $dto->{$descendant} instanceof BaseDtoCollection => $dto->{$descendant}->exceptDtoKeys(...$properties),
                    $dto->{$descendant} instanceof self => $dto->{$descendant}->except(...$properties),
                    default => throw new \Exception("Don't support " . gettype($descendant) . " type for except method")
                };

                continue;
            }

            $dto->exceptKeys = [...$dto->exceptKeys, ...$properties];
        }

        return $dto;
    }

    public function clone(...$args): static
    {
        return new static(array_merge($this->toArray(), ...$args));
    }

    /**
     * Get public properties.
     *
     * @return array
     */
    public function getPublicProperties(): array
    {
        return (new ReflectionClass($this))->getProperties(ReflectionProperty::IS_PUBLIC);
    }

    protected function parseArray(array $array): array
    {
        foreach ($array as $key => $value) {
            if ($value instanceof DataTransferObject || $value instanceof Arrayable) {
                $array[$key] = $value->toArray();

                continue;
            }

            if (!is_array($value)) {
                continue;
            }

            $array[$key] = $this->parseArray($value);
        }

        return $array;
    }
}
