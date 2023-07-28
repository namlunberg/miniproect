<?php
namespace Services;

class TestingOfQueryBuilder
{
    private string $tableName;
    private string $select;
    private string $insertOne;
    private string $insert;
    private string $update;
    private string $delete;
    private string $where;
    private string $orderBy;
    private string $limit;
    private string $innerJoin;

    public function __construct(string $tableName)
    {
        $this->tableName = $tableName;
    }

    public function getSelect(): string
    {
        return $this->select;
    }

    public function getInsertOne(): string
    {
        return $this->insertOne;
    }
    public function getInsert(): string
    {
        return $this->insert;
    }

    public function getUpdate(): string
    {
        return $this->update;
    }

    public function getDelete(): string
    {
        return $this->delete;
    }

    public function getWhere(): string
    {
        return $this->where;
    }

    public function getOrderBy(): string
    {
        return $this->orderBy;
    }

    public function getLimit(): string
    {
        return $this->limit;
    }

    public function getInnerJoin(): string
    {
        return $this->innerJoin;
    }

    public function select(array|string $condition = "*"): self
    {
        $selectString = "";

        if (is_array($condition)) {

            if (!isset($condition[0])) {

                foreach ($condition as $altName => $columnName) {
                    $selectString .= "$columnName as $altName, ";
                }

            } else {

                foreach ($condition as $columnName) {
                    $selectString .= "$columnName, ";
                }

            }

            $selectString = rtrim($selectString, ", ");

        } else {
            $selectString = $condition;
        }

        $this->select = "SELECT $selectString FROM $this->tableName ";
        $this->valuesArray["tableName"] = $name;
        return $this;
    }

    public function insertOne(array|string $insertParams): self
    {
        $insertString = "";
        $columnNamePart = "";
        $insertParamPart = "";

        if (is_array($insertParams)) {
            foreach ($insertParams as $columnName => $insertParam) {
                $columnNamePart .= "$columnName, ";
                $insertParamPart .= "'" . $insertParam . "',";
            }
            $columnNamePart = trim($columnNamePart, ", ");
            $insertParamPart = trim($insertParamPart, ", ");
            $insertString .= "($columnNamePart) VALUES ($insertParamPart)";
        } else {
            $insertString .= $insertParams;
        }

        $this->insertOne = "INSERT INTO $this->tableName$insertString ";
        return $this;
    }
    public function insert(array|string $insertParams, array $insertNames=[]): self
    {
        $insertString = "";
        $insertNamePart  = "";
        $insertParamPart = "";
        if (is_array($insertParams)) {

            foreach ($insertParams as $insertParamString) {
                if (is_array($insertParamString)) {
                    $insertParamPart .= "(";
                    foreach ($insertParamString as $insertParam) {
                        $insertParamPart .= "'" . $insertParam . "', ";
                    }
                    $insertParamPart = rtrim($insertParamPart, ", ");
                    $insertParamPart .= "), ";
                } else {
                    if (!stripos($insertParamPart, "(")) {
                        $insertParamPart .= "(";
                    }
                    $insertParamPart .= "'" . $insertParamString . "', ";
                }
            }
            $insertParamPart = rtrim($insertParamPart, ", ");
            if (!stripos($insertParamPart, ")")) {
                $insertParamPart .= ")";
            }

            $insertNamePart .= "(";
            foreach ($insertNames as $insertName) {
                $insertNamePart .= "$insertName, ";
            }
            $insertNamePart = rtrim($insertNamePart, ", ");
            $insertNamePart .= ")";

            $insertString .= "$insertNamePart VALUES $insertParamPart";
        } else {
            $insertString .= $insertParams;
        }

        $this->insert = "INSERT INTO $this->tableName$insertString ";
        return $this;
    }

    public function update(array|string $condition): self
    {
        $updateString = "";

        if (is_array($condition)) {
            foreach ($condition as $columnName => $value) {
                $updateString .= "$columnName = '" . $value . "', ";
            }
            $updateString = rtrim($updateString, ", ");
        }

        $this->update = "UPDATE $this->tableName SET $updateString ";
        return $this;
    }

    public function delete(): self
    {
        $this->delete = "DELETE FROM $this->tableName ";
        return $this;
    }

    public function where(array|string $condition, array $methods=[]): self
    {
        $whereString = "";

        if (is_array($condition)) {
            foreach ($condition as $columnName => $value) {
                foreach ($methods as $methodName => $method) {
                    if ($columnName === $methodName) {
                        $whereString .= "$columnName $method  '" . $value . "' AND ";
                        continue 2;
                    }
                }
                $whereString .= "$columnName = '" . $value . "' AND ";
            }
            $whereString = mb_substr($whereString, 0, -5);
        } else {
            $whereString = $condition;
        }

        $this->where = "WHERE $whereString ";
        return $this;
    }

    public function orderBy(string $criterion, string $method="ASC"): self
    {
        $this->orderBy = "ORDER BY $criterion $method ";
        return $this;
    }

    public function limit(int $limit, int $offset = 0): self
    {
        $this->limit = "LIMIT $limit OFFSET $offset";
        return $this;
    }

    public function join(string $joinTableName, array|string $joinParams): self
    {
        $joinString = "";

        if (is_array($joinParams)) {
            foreach ($joinParams as $curParam => $joinParam) {
                $joinString .= "$this->tableName.$curParam = $joinTableName.$joinParam, ";
            }
            $joinString = rtrim($joinString, ", ");
        } else {
            $joinString = $joinParams;
        }

        $this->innerJoin = "INNER JOIN $joinTableName ON $joinString ";
        return $this;
    }
}