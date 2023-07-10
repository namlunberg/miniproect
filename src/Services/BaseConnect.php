<?php

namespace Services;

use mysqli;

class BaseConnect
{
    private string $hostName;
    private string $user;
    private string $password = "";
    private string $baseName;
    private \mysqli $conn;
    private string $tableName;

    public function getConnect(): mysqli
    {
        return $this->conn;
    }

    public function __construct(string $hostName, string $user, string $baseName, string $password = "")
    {
        $this->hostName = $hostName;
        $this->user = $user;
        $this->baseName = $baseName;
        $this->password = $password;
    }

    public function connect(): mysqli
    {
        $this->conn = new mysqli($this->hostName, $this->user, $this->password, $this->baseName);
        if ($this->conn->connect_error) {
            die("Ошибка: " . $this->conn->connect_error);
        }
        return $this->conn;
    }

    public function query(string $query): bool|\mysqli_result
    {
        return $this->conn->query($query);
    }

    public function getRows(string $query): array
    {
        $result = [];

        $rows = $this->query($query);
        while ($row = mysqli_fetch_assoc($rows)) {
            $result[] = $row;
        }

        return $result;
    }

    public function setTableName (string $tableName): self
    {
        $this->tableName = $tableName;

        return $this;
    }

    public function getTableName (): string
    {
        return $this->tableName;
    }

    public function findById (int $id): ?array
    {
        $query = "SELECT * FROM " . $this->getTableName() . " WHERE id =" . $id;
        return $this->getRows($query);
    }

    public function findAll(): array
    {
        $query = "SELECT * FROM " . $this->getTableName();
        return $this->getRows($query);
    }

    public function findBy(array $condition): array
    {
        $query ="SELECT * FROM " . $this->getTableName() . " WHERE ";
        foreach ($condition as $key => $value) {
            if (is_array($value)) {
                $query .= "$key IN (" . implode(',', $value) . " AND )";
            } else {
                $query .= "$key = $value AND ";
            }
        }
        preg_match(pattern: "/ AND $/", subject: $query, matches: $result);
        $query = trim($query, $result[0]);
        return $this->getRows($query);
    }

    public function insertBy(array $condition): void
    {
        $conditionKeys = array_keys($condition);
        $tableNames = implode(", ", $conditionKeys);
        $tableValues = implode("', '", $condition);

        $query = "INSERT INTO " . $this->getTableName() . "( " . $tableNames . " ) VALUES ( '" . $tableValues . "' )";
        $this->query($query);
    }
}
