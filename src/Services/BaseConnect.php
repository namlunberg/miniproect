<?php

namespace Services;

use mysqli;

class BaseConnect
{
    protected static string $hostName;
    protected static string $user;
    protected static string $password = "";
    protected static string $baseName;
    protected static \mysqli $conn;

    protected string $tableName;

    public function getConnect(): mysqli
    {
        return self::$conn;
    }

    public static function setHostName(string $name): void
    {
        self::$hostName = $name;
    }

    public static function setUser(string $user): void
    {
        self::$user = $user;
    }

    public static function setPassword(string $pass): void
    {
        self::$password = $pass;
    }

    public static function setBaseName(string $name): void
    {
        self::$baseName = $name;
    }

    public static function connect(): mysqli
    {
        self::$conn = new mysqli(self::$hostName, self::$user, self::$password, self::$baseName);
        if (self::$conn->connect_error) {
            die("Ошибка: " . self::$conn->connect_error);
        }
        return self::$conn;
    }

}
