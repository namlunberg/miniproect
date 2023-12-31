<?php

namespace Services\Requests;

class Session extends BaseGlobalsArray
{
    public function getField(string $fieldName): ?string
    {
        return $_SESSION[$fieldName] ?? null;
    }

    public function getAll(): ?array
    {
        return $_SESSION ?? null;
    }

    public function setField(string $name, mixed $value = false): void
    {
        $_SESSION[$name] = $value;
    }
    public function removeField(string $name): void
    {
        unset($_SESSION[$name]);
    }

    public function isActive(): bool
    {
        if (!empty($_SESSION)) {
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }
}