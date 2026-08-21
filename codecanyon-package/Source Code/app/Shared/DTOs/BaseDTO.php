<?php

declare(strict_types=1);

namespace App\Shared\DTOs;

use Illuminate\Http\Request;
use ReflectionClass;
use ReflectionProperty;

abstract class BaseDTO
{
    /**
     * Create a DTO instance from an array of data.
     */
    public static function fromArray(array $data): static
    {
        $class = new ReflectionClass(static::class);
        $constructor = $class->getConstructor();
        
        if ($constructor === null) {
            return new static();
        }

        $parameters = $constructor->getParameters();
        $args = [];

        foreach ($parameters as $parameter) {
            $name = $parameter->getName();
            
            if (array_key_exists($name, $data)) {
                $args[$name] = $data[$name];
            } elseif ($parameter->isDefaultValueAvailable()) {
                $args[$name] = $parameter->getDefaultValue();
            } else {
                $args[$name] = null; // Or throw an exception depending on preference
            }
        }

        return new static(...$args);
    }

    /**
     * Create a DTO instance from a Form Request.
     */
    public static function fromRequest(Request $request): static
    {
        return static::fromArray($request->validated());
    }

    /**
     * Convert the DTO to an array.
     */
    public function toArray(): array
    {
        $class = new ReflectionClass($this);
        $properties = $class->getProperties(ReflectionProperty::IS_PUBLIC);
        $data = [];

        foreach ($properties as $property) {
            if ($property->isStatic()) {
                continue;
            }
            
            $name = $property->getName();
            $value = $property->getValue($this);

            if ($value instanceof self) {
                $data[$name] = $value->toArray();
            } elseif (is_array($value)) {
                $data[$name] = array_map(
                    fn($item) => $item instanceof self ? $item->toArray() : $item,
                    $value
                );
            } else {
                $data[$name] = $value;
            }
        }

        return $data;
    }
}
