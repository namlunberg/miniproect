<?php

namespace Controllers;

use Services\Pagination;

use Services\Repositories\ReviewsRepository;
use Services\Requests\Sender;
use Services\ServiceContainer;

class ReviewsController extends BaseController
{
    private Sender $sender;
    private ReviewsRepository $reviewsRepository;
    public function __construct()
    {
        parent::__construct();
        $this->reviewsRepository = ServiceContainer::getService('reviewsRepository');
        $this->sender = new Sender();
    }
    public function actionList():void
    {
        if (!empty($this->postGetter->getField("insert"))) {
            $this->reviewsRepository->insertBy(["name" => $this->postGetter->getField("name"), "review" => $this->postGetter->getField("review")]);
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



        $this->templateBuilder([
            "layout/header",
            "reviews/list",
            "layout/footer"
        ], [
            'reviewsRows' => $this->reviewsRepository->getPaginationRows($page),
            'sessionGetter' => $this->sessionGetter,
        ], [
            "pageNumber" => $page,
            "sumPages" => $this->reviewsRepository->getPagination()->sumPages(),
        ]);
        if ($this->sessionGetter->getField("success")) {
            $this->sessionGetter->removeField("success");
        }
    }
}