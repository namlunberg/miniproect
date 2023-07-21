<?php

namespace Controllers;

use Services\ServiceContainer;

class NewsController extends BaseController {
public function __construct()
{
    parent::__construct();
    $connect = ServiceContainer::getService('connect');
    $request = ServiceContainer::getService('request');
    $connect->setTableName("news");
}

    public function actionNews():void
    {
        $this->templateBuilder([
            "layout/header",
            "news/list",
            "layout/footer"
        ], [
            "newsRows" => $this->connect->findAll(),
        ]);
    }
}