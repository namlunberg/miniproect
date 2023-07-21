<?php

namespace Controllers;

use Services\Pagination;
use Services\Requests\Sender;
use Services\ServiceContainer;

class ReviewsController extends BaseController
{
    private Sender $sender;
    public function __construct()
    {
        parent::__construct();
        $connect = ServiceContainer::getService('connect');
        $request = ServiceContainer::getService('request');
        $connect->setTableName("reviews");
        $this->sender = new Sender($connect);
    }

    public function actionList():void
    {
        if (!empty($this->postGetter->getField("insert"))) {
            $this->connect->insertBy(["name" => $this->postGetter->getField("name"), "review" => $this->postGetter->getField("review")]);
            $this->sessionGetter->setField("success", true);
            $this->sender->sendMailToEachAdmin();
            $currentUrl = $this->request->getCurrentUrl();
            $currentUrl = explode('?', $currentUrl);
            $currentUrl = $currentUrl[0];
            header("location:" . $currentUrl);
            exit;
        }

        $page = $this->request->getGet()->getField("page");
        if (empty($page)) {
            $page = 1;
        }

        $pagination = new Pagination($this->connect->getTableName(), ["create_time" => "DESC"], 5, $this->connect->countRows($this->connect->countQueryBy(["status"=>"1"])), $page);

        $this->templateBuilder([
            "layout/header",
            "reviews/list",
            "layout/footer"
        ], [
            'reviewsRows' => $this->connect->query($pagination->buildQuery(["status" => "1"])),
            'sessionGetter' => $this->sessionGetter,
        ], [
            "pageNumber" => $pagination->getPageNumber(),
            "sumPages" => $pagination->sumPages(),
        ]);
        if ($this->sessionGetter->getField("success")) {
            $this->sessionGetter->removeField("success");
        }
    }
}