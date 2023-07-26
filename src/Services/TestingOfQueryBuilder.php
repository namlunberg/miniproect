<?php
namespace Services;

class TestingOfQueryBuilder
{
    private string $tableName;
    private string $select;
    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
    }

    public function Select(array $something): void
    {
        $this->select = "SELECT " . $something . " FROM" . $this->tableName;
    }
}