<?php

namespace Controllers;

use Services\BaseRepository;
use Services\Pagination;
use Services\Requests\Sender;
use Services\ServiceContainer;

class ReviewsController extends BaseController
{
    private Sender $sender;
    private BaseRepository $reviewsTableConnect;
    public function __construct()
    {
        parent::__construct();
        $this->reviewsTableConnect = ServiceContainer::getService('reviewsTableConnect');
        $this->sender = new Sender();
    }

    public function actionList():void
    {
        if (!empty($this->postGetter->getField("insert"))) {
            $this->reviewsTableConnect->insertBy(["name" => $this->postGetter->getField("name"), "review" => $this->postGetter->getField("review")]);
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

        $pagination = new Pagination($this->reviewsTableConnect->getTableName(), ["create_time" => "DESC"], 5, $this->reviewsTableConnect->countRows($this->reviewsTableConnect->countQueryBy(["status"=>"1"])), $page);

        $this->templateBuilder([
            "layout/header",
            "reviews/list",
            "layout/footer"
        ], [
            'reviewsRows' => $this->reviewsTableConnect->query($pagination->buildQuery(["status" => "1"])),
            'sessionGetter' => $this->sessionGetter,
            'rowsOnPage' => "5",
            'sumRows' => $this->reviewsTableConnect->countRows($this->reviewsTableConnect->countQueryBy(["status"=>"1"])),
        ], [
            "pageNumber" => $pagination->getPageNumber(),
            "sumPages" => $pagination->sumPages(),
        ]);
        if ($this->sessionGetter->getField("success")) {
            $this->sessionGetter->removeField("success");
        }
    }
}