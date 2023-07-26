<?php

namespace Services;

class Pagination
{
    protected string $query;
    protected int $rowsOnPage;
    protected int $sumRows;
    protected int $pageNumber;

    public function __construct(string $query, int $pageNumber, int $rowsOnPage)
    {
        $this->query = $query;
        $this->rowsOnPage = $rowsOnPage;
        $this->pageNumber = $pageNumber;
    }

    public function setSumRows(int $sumRows): void
    {
        $this->sumRows = $sumRows;
    }

    public function getPageNumber(): int
    {
        return $this->pageNumber;
    }

    public function rowsSlice(): int
    {
        return ($this->getPageNumber()*$this->rowsOnPage)-$this->rowsOnPage;
    }

    public function orderBuild(array $orderBuild): string
    {
        $string = "";
        foreach ($orderBuild as $key => $value) {
            $string .= "$key " . "$value,";
        }
        return " ORDER BY " . rtrim($string, ",");
    }

    public function prepareQuery(array $arrayCondition=[], array $orderByArray=[]): string
    {
        if (!empty($arrayCondition)){
            $condition = $this->paginationWhereCondition($arrayCondition);
        } else {
            $condition = "";
        }
        if (!empty($orderByArray)){
            $orderByArray = $this->orderBuild($orderByArray);
        } else {
            $orderByArray = "";
        }
        return $condition . $orderByArray;
    }

    public function buildQuery(string $preparedQuery): string
    {
        return $this->query . $preparedQuery . " LIMIT " . $this->rowsSlice() . ", " . $this->rowsOnPage;
    }

    public function paginationWhereCondition(array $condition): string
    {
        $result = "";
        foreach ($condition as $key => $value) {
            $result .= " $key = '" . $value . "',";
        }
        $result = rtrim($result, ",");
        return " WHERE" . $result;
    }

    public function sumPages(): int
    {
        return ceil($this->sumRows/$this->rowsOnPage);
    }



}