<?php

namespace Services;

class Pagination
{
    private string $tableName;
    private array $orderByArray;
    private int $rowsOnPage;
    private int $sumRows;
    private int $pageNumber;

    public function __construct(string $tableName, array $orderByArray, int $rowsOnPage, int $sumRows, int $pageNumber)
    {
        $this->tableName = $tableName;
        $this->orderByArray = $orderByArray;
        $this->rowsOnPage = $rowsOnPage;
        $this->sumRows = $sumRows;
        $this->pageNumber = $pageNumber;
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
        return rtrim($string, ",");
    }

    public function buildQuery(): string
    {
        return "SELECT * FROM " . $this->tableName . " ORDER BY " . $this->orderBuild($this->orderByArray) . " LIMIT " . $this->rowsSlice() . ", " . $this->rowsOnPage;
    }

    public function sumPages(): int
    {
        return ceil($this->sumRows/$this->rowsOnPage);
    }



}