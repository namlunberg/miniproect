<?php

namespace Services\Requests;

class Cookies extends BaseGlobalsArray
{

    public function createCookie(string $name, mixed $value, int $lifetime): void
    {
        setcookie($name, $value, time() + $lifetime, "/");
    }
    public function getField(string $fieldName): ?string
    {
        return $_COOKIE[$fieldName] ?? null;
    }

    public function getAll(): ?array
    {
        return $_COOKIE ?? null;
    }

    public function setField(string $name, mixed $value = false): void
    {
        $_COOKIE[$name] = $value;
    }
    public function removeField(string $name): void
    {
        unset($_COOKIE[$name]);
    }

    public function isActive(): bool
    {
        if (!empty($_COOKIE)) {
            $result = true;
        } else {
            $result = false;
        }

        return $result;
    }
}

