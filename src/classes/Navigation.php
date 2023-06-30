<?php

namespace src\classes;

class Navigation
{
    private int $rowsOnPage;
    private int $numRows;
    private array $navArray;
    private ?int $getParam = NULL;
    private int $nav;
    private int $str;

    public function getRows():int
    {
        return $this->rowsOnPage;
    }

    public function getStr():int
    {
        return $this->str;
    }

    public function __construct(int $numRows, ?int $getParam=NULL, int $rowsOnPage=5, array $navArray=[])
    {
        $this->numRows = $numRows;
        $this->getParam=$getParam;
        $this->rowsOnPage=$rowsOnPage;
        $this->navArray=$navArray;
    }

    public function navigationParamCalculate ():int
    {
        return ceil($this->numRows / $this->rowsOnPage);
    }

    public function navigationBuild():array
    {
        $numRows = $this->navigationParamCalculate();
        if (isset($this->getParam)) {
            $this->nav = $this->getParam;
            $this->str = $this->getParam * $this->rowsOnPage - $this->rowsOnPage;
        } else {
            $this->nav = 0;
            $this->str = 0;
        }

        for ($i = 1; $i <= $numRows; $i++) {
            if ($i === 1) {
                $this->navArray[] = '<li' . (($this->str === 0) ? ' class="active"' : "") . '><a href="/">' . $i . '</a></li>';
            } elseif ($i !== $this->nav) {
                $this->navArray[] = '<li><a href="?page=' . $i . '">' . $i . '</a></li>';
            } else {
                $this->navArray[] = '<li class="active"><a href="?page=' . $i . '">' . $i . '</a></li>';
            }
        }
        return $this->navArray;
    }
}