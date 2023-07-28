<?php

namespace Services;

use mysqli;

class QueryBuilder
{
    private string $queryMode;
    private string $tableName;
    private string $select;
    private string $insertColumns;
    private string $insertValues;
    private string $update;
    private string $where;
    private string $orderBy;
    private string $orderByMethod;
    private string $limit;
    private string $offset;
    private string $joinTableName;
    private string $join;

    private string $query;

    public function getQueryMode(): string
    {
        return $this->queryMode;
    }
    public function getTableName(): string
    {
        return $this->tableName;
    }
    public function getSelect(): string
    {
        return $this->select;
    }
    public function getInsertColumns(): string
    {
        return $this->insertColumns;
    }
    public function getInsertValues(): string
    {
        return $this->insertValues;
    }
    public function getUpdate(): string
    {
        return $this->update;
    }
    public function getWhere(): string
    {
        return $this->where;
    }
    public function getOrderBy(): string
    {
        return $this->orderBy;
    }
    public function getOrderByMethod(): string
    {
        return $this->orderByMethod;
    }
    public function getLimit(): string
    {
        return $this->limit;
    }
    public function getOffset(): string
    {
        return $this->offset;
    }
    public function getJoinTableName(): string
    {
        return $this->joinTableName;
    }
    public function getJoin(): string
    {
        return $this->join;
    }

    public function getQuery(): string
    {
        return $this->query;
    }

    public function select(string $tableName, array $condition): self
    {
        $selectPart = "";
        foreach ($condition as $value) {
            $selectPart .= "$value, ";
        }
        $selectPart = rtrim($selectPart, ", ");

        $this->select = $selectPart;
        $this->tableName = $tableName;
        $this->queryMode = "select";
        return $this;
    }
    public function insert(string $tableName, array $insertColumns, array $insertValues): self
    {
        $insertColumnsPart = "(";
        foreach ($insertColumns as $insertColumn) {
            $insertColumnsPart .= "$insertColumn, ";
        }
        $insertColumnsPart = rtrim($insertColumnsPart, ", ");
        $insertColumnsPart .= ")";

        $insertValuesPart = "";
        foreach ($insertValues as $insertValue) {
            if (is_array($insertValue)) {
                $insertValuesPart .= "(";
                foreach ($insertValue as $item) {
                    $insertValuesPart .= "'" . $item . "', ";
                }
                $insertValuesPart = rtrim($insertValuesPart, ", ");
                $insertValuesPart .= ") ";
            } else {
                if (!stripos($insertValuesPart, "(")) {
                    $insertValuesPart .= "(";
                }
                $insertValuesPart .= "'" . $insertValue . "', ";
            }
        }
        $insertValuesPart = rtrim($insertValuesPart, ", ");
        if (!stripos($insertValuesPart, ")")) {
            $insertValuesPart .= ")";
        }

        $this->insertColumns = $insertColumnsPart;
        $this->insertValues = $insertValuesPart;
        $this->tableName = $tableName;
        $this->queryMode = "insert";
        return $this;
    }
    public function update(string $tableName, array $condition): self
    {
        $updatePart = "";
        foreach ($condition as $columnName => $columnValue) {
            $updatePart .= "$columnName = '" . $columnValue . "', ";
        }
        $updatePart = rtrim($updatePart, ", ");

        $this->update = $updatePart;
        $this->tableName = $tableName;
        $this->queryMode = "update";
        return $this;
    }
    public function delete(string $tableName): self
    {
        $this->tableName = $tableName;
        $this->queryMode = "delete";
        return $this;
    }
    public function where(array $condition, array $methods=[]): self
    {
        $wherePart = "";
        foreach ($condition as $columnName => $value) {
            if (!empty($methods)) {
                foreach ($methods as $methodName => $method) {
                    if ($columnName === $methodName) {
                        $wherePart .= "$columnName $method  '" . $value . "' AND ";
                        continue 2;
                    }
                }
            }
            $wherePart .= "$columnName = '" . $value . "' AND ";
        }
        $wherePart = mb_substr($wherePart, 0, -5);

        $this->where = $wherePart;
        return $this;
    }
    public function orderBy(string|array $criterion, string $method="ASC"): self
    {
        $orderByPart = "";
        if (is_array($criterion)) {
            foreach ($criterion as $value) {
                $orderByPart .= "$value, ";
            }
            $orderByPart = rtrim($orderByPart, ", ");
        } else {
            $orderByPart .= $criterion;
        }

        $this->orderBy = $orderByPart;
        $this->orderByMethod = $method;
        return $this;
    }
    public function limit(int $limit, int $offset = 0): self
    {
        $this->limit = $limit;
        $this->offset = $offset;
        return  $this;
    }
    public function join(string $joinTableName, array|string $joinParams):self
    {
        $joinPart = "";
        foreach ($joinParams as $curParam => $joinParam) {
            $joinPart .= "$this->tableName.$curParam = $joinTableName.$joinParam, ";
        }
        $joinPart = rtrim($joinPart, ", ");

        $this->joinTableName = $joinTableName;
        $this->join = $joinPart;
        return $this;
    }

    public function query(): self
    {
        $query = "";

        switch ($this->getQueryMode()) {
            case "select":
                $query = "SELECT $this->select FROM $this->tableName " .
                    (!empty($this->join) ? "JOIN $this->joinTableName ON $this->join " : "").
                    (!empty($this->where) ? "WHERE $this->where " : "") .
                    (!empty($this->orderBy) ? "ORDER BY $this->orderBy $this->orderByMethod " : "") .
                    (!empty($this->limit) ? "LIMIT $this->limit OFFSET $this->offset " : "");
                break;
            case "insert":
                $query = "INSERT INTO $this->tableName $this->insertColumns VALUES $this->insertValues";
                break;
            case "update":
                $query = "UPDATE $this->tableName SET $this->update " .
                    (!empty($this->where) ? "WHERE $this->where" : "");
                break;
            case  "delete":
                $query = "DELETE FROM $this->tableName " .
                    (!empty($this->where) ? "WHERE $this->where" : "");
                break;
            default:
                break;
        }

        $query = rtrim($query);
        $this->query = $query;
        return $this;
    }
}