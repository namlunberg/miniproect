<?php

namespace Controllers;

use Services\BaseConnect;

class NewsController {
    private BaseConnect $connect;
    public function __construct(BaseConnect $connect)
    {
        $this->connect = $connect;
        $connect->setTableName("news");
    }

    public function getConnect(): BaseConnect
    {
        return $this->connect;
    }

    public function actionNews():void
    {
        $newsRows = $this->connect->findAll();
        include $_SERVER['DOCUMENT_ROOT'] . "/templates/header.php";
        include $_SERVER['DOCUMENT_ROOT'] . "/templates/news.php";
        include $_SERVER['DOCUMENT_ROOT'] . "/templates/footer.php";
    }
}