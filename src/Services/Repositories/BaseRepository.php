<?php

namespace Services\Repositories;

use \Services\BaseConnect;

abstract class BaseRepository extends BaseConnect
{
    protected string $tableName;

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

}