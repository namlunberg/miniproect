<?php

namespace Services;


class ServiceContainer
{
    private static array $services = [];

    public static function addService(string $name, object $value): void
    {
        self::$services[$name] = $value;
    }

    public static function getService($name): object
    {
        return self::$services[$name];
    }

}
