<?php

namespace Services;

class BaseRepository extends BaseConnect
{


    public function setTableName (string $tableName): self
    {
        $this->tableName = $tableName;

        return $this;
    }

    public function getTableName (): string
    {
        return $this->tableName;
    }
}