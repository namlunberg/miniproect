<?php

namespace Controllers;

use Services\BaseConnect;
use Services\Requests\Request;

class ReviewsController {
    private BaseConnect $connect;
    private Request $request;

    public function __construct(BaseConnect $connect, Request $request)
    {
        $this->connect = $connect;
        $this->request = $request;
        $connect->setTableName("reviews");
    }

    public function getConnect(): BaseConnect
    {
        return $this->connect;
    }

    public function actionList():void
    {
        $postGetter = $this->request->getPost();
        $sessionGetter = $this->request->getSession();

        if (!empty($postGetter->getField("insert"))) {
            $this->connect->insertBy(["name" => $postGetter->getField("name"), "review" => $postGetter->getField("review")]);

            $sessionGetter->setField("success", true);
            $currentUrl = $this->request->getCurrentUrl();


            $currentUrl = explode('?', $currentUrl);
            $currentUrl = $currentUrl[0];
            header("location:" . $currentUrl);
            exit;
        }
        $reviewsRows = $this->connect->findAll();
        include $_SERVER['DOCUMENT_ROOT'] . "/templates/header.php";
        include $_SERVER['DOCUMENT_ROOT'] . "/templates/list.php";
        include $_SERVER['DOCUMENT_ROOT'] . "/templates/footer.php";
        if ($sessionGetter->getField("success")) {
            $sessionGetter->removeField("success");
        }
    }
}