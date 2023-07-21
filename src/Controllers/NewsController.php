<?php

namespace Controllers;

use Services\BaseRepository;
use Services\ServiceContainer;

class NewsController extends BaseController {
    private BaseRepository $newsTableConnect;

    public function __construct()
    {
        parent::__construct();
        $this->newsTableConnect = ServiceContainer::getService('newsTableConnect');
    }

    public function actionNews():void
    {
        $this->templateBuilder([
            "layout/header",
            "news/list",
            "layout/footer"
        ], [
            "newsRows" => $this->newsTableConnect->findAll(),
        ]);
    }
}