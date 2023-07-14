<?php

namespace Services\Requests;

abstract class BaseGlobalsArray
{
    protected ?array $array;

    public function getField(string $fieldName): ?string
    {
        return $this->array[$fieldName] ?? null;
    }

    public function getAll(): ?array
    {
        return $this->array ?? null;
    }

    public function setField(string $name, mixed $value=false): void
    {
        $this->array[$name] = $value;
    }
    public function removeField(string $name): void
    {
        unset($this->array[$name]);
    }

    public function isActive (): bool
    {
        if (!empty($this->array)) {
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }
}