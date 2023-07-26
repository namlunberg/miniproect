<?php

namespace Controllers;

use Services\Repositories\NewsRepository;
use Services\ServiceContainer;

class NewsController extends BaseController {
    private NewsRepository $newsConnect;

    public function __construct()
    {
        parent::__construct();
        $this->newsConnect = ServiceContainer::getService('newsConnect');
    }

    public function actionNews():void
    {
        $this->templateBuilder([
            "layout/header",
            "news/list",
            "layout/footer"
        ], [
            "newsRows" => $this->newsConnect->findAll(),
        ]);
    }
}