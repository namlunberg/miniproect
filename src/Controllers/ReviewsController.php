<?php

namespace Controllers;

use Services\BaseConnect;
use Services\Pagination;
use Services\Requests\Request;

class ReviewsController extends BaseController
{
    public function __construct(BaseConnect $connect, Request $request)
    {
        parent::__construct($connect, $request);
        $connect->setTableName("reviews");
    }

    public function actionList():void
    {

        if (!empty($this->postGetter->getField("insert"))) {
            $this->connect->insertBy(["name" => $this->postGetter->getField("name"), "review" => $this->postGetter->getField("review")]);
            $this->sessionGetter->setField("success", true);
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

        $pagination = new Pagination($this->connect->getTableName(), ["create_time" => "DESC"], 5, $this->connect->countAll(), $page);

        $this->templateBuilder([
            "layout/header",
            "reviews/list",
            "layout/footer"
        ], [
            'reviewsRows' => $this->connect->query($pagination->buildQuery()),
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