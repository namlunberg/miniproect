<?php

namespace Controllers;

use Services\BaseConnect;
use Services\Requests\Request;

class NewsController extends BaseController {
public function __construct(BaseConnect $connect, Request $request)
{
    parent::__construct($connect, $request);
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