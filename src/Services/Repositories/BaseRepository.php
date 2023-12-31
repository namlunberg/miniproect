<?php

namespace Services\Repositories;

use Services\BaseConnect;
use Services\QueryBuilder;

abstract class BaseRepository extends BaseConnect
{
    protected string $tableName;
    protected QueryBuilder $queryBuilder;

    protected function __construct()
    {
        $this->setTableName($this->tableName);
    }

    public function setTableName(string $tableName): self
    {
        $this->tableName = $tableName;
        return $this;
    }

    public function getTableName (): string
    {
        return $this->tableName;
    }

    public function query(string $query): bool|\mysqli_result
    {
        return self::$conn->query($query);
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

    public function findById (int $id): ?array
    {
        $query = "SELECT * FROM " . $this->getTableName() . " WHERE id =" . $id;
        return mysqli_fetch_assoc($this->query($query));
    }

    public function findAll(): array
    {
        $query = "SELECT * FROM " . $this->getTableName();
        return $this->getRows($query);
    }

    public function whereCondition(array $condition): string
    {
        $query ="SELECT * FROM " . $this->getTableName() . " WHERE ";
        foreach ($condition as $key => $value) {
            if (is_array($value)) {
                $query .= "$key IN ('" . implode('\',\'', $value) . "') AND ";
            } else {
                $query .= "$key = '$value' AND ";
            }
        }
        preg_match(pattern: "/ AND $/", subject: $query, matches: $result);
        return trim($query, $result[0]);
    }

    public function findBy(array $condition): array
    {
        $query = $this->whereCondition($condition);
        return $this->getRows($query);
    }

    public function findOne(array $condition): ?array
    {
        $query = $this->whereCondition($condition) . " LIMIT 1";
        return mysqli_fetch_assoc($this->query($query));
    }

    public function insertBy(array $condition): void
    {
        $conditionKeys = array_keys($condition);
        $tableNames = implode(", ", $conditionKeys);
        $tableValues = implode("', '", $condition);

        $query = "INSERT INTO " . $this->getTableName() . "( " . $tableNames . " ) VALUES ( '" . $tableValues . "' )";
        $this->query($query);
    }

    public function countQueryAll(): string
    {
        return "SELECT COUNT(*) FROM " . $this->getTableName();
    }

    public function countQueryBy(array $condition): string
    {
        $query = "SELECT COUNT(*) FROM " . $this->getTableName() . " WHERE ";
        foreach ($condition as $key => $value) {
            if (is_array($value)) {
                $query .= "$key IN ('" . implode('\',\'', $value) . "') AND ";
            } else {
                $query .= "$key = '$value' AND ";
            }
        }
        preg_match(pattern: "/ AND $/", subject: $query, matches: $result);
        return trim($query, $result[0]);
    }

    public function countRows(string $query): string
    {
        return mysqli_fetch_assoc($this->query($query))['COUNT(*)'];
    }

    public function updateBy(array $fields, int $id): void
    {
        $query = "UPDATE " . $this->getTableName() . " SET";
        foreach ($fields as $key => $value){
            $query .= " $key = '" . $value . "',";
        }
        $query = rtrim($query, ",");

        $query .= " WHERE id = '" . $id . "'";

        $this->query($query);
    }

    public function deleteById(int $id): void
    {
        $query = "DELETE FROM " . $this->getTableName() . " WHERE id='" . $id . "'";
        $this->query($query);
    }

    public function joinSelect(string $joinTable, array $needleParams, string $joinCheckParam, string $checkParam): array
    {
        $query = "SELECT " . $this->getTableName() . ".*, ";
        foreach ($needleParams as $value) {
            $query .= "$joinTable.$value, ";
        }
        $query = rtrim($query, ", ");
        $query .= " FROM " . $this->getTableName() . " JOIN " . $joinTable . " ON $joinTable.$joinCheckParam = " . $this->getTableName() . ".$checkParam";
        return $this->getRows($query);
    }
}