<?php
namespace Controllers\Admin;

use Controllers\BaseController;
use Services\BaseRepository;
use Services\Pagination;
use Services\Security;
use Services\ServiceContainer;

class ReviewsCrudController extends BaseController
{
    protected Security $security;
    protected BaseRepository $usersTableConnect;
    protected BaseRepository $reviewsTableConnect;
    public function __construct()
    {
        parent::__construct();
        $this->security=ServiceContainer::getService("security");
        $this->usersTableConnect = ServiceContainer::getService("usersTableConnect");
        $this->reviewsTableConnect = ServiceContainer::getService("reviewsTableConnect");

        if (!$this->security->isAuth()) {
            header("location:/auth");
        }


    }

    public function adminCheck(): void
    {
        $adminDate = $this->usersTableConnect->findOne(["sid"=>$this->coockieGetter->getField("sid")]);

        $this->reviewsTableConnect->updateBy(["adminId" => $adminDate["id"]], $this->getGetter->getField("id"));
    }

    public function actionReviews(): void
    {
        $page = $this->request->getGet()->getField("page");
        if (empty($page)) {
            $page = 1;
        }

        $pagination = new Pagination($this->reviewsTableConnect->getTableName(), ["status" => "ASC"], 5, $this->reviewsTableConnect->countRows($this->reviewsTableConnect->countQueryAll()), $page);

        $this->templateBuilder([
            "adminLayout/header",
            "admin/reviewsCrud",
            "adminLayout/footer"
        ], [
            "reviewsRows" => $this->reviewsTableConnect->query($pagination->buildQuery()),
            "adminActivity" => $this->reviewsTableConnect->joinSelect("users", ['login'], "id", "adminId"),
            "currentUrl" => "/admin/reviewsCrud",
        ], [
            "pageNumber" => $pagination->getPageNumber(),
            "sumPages" => $pagination->sumPages(),
        ]);
    }

    public function actionReviewUpdate(): void
    {
        if (!empty($this->postGetter->getField("update"))){
            $updateArray = [];
            foreach ($this->postGetter->getAll() as $key => $value) {
                $updateArray[$key] = $value;
            }
            unset($updateArray["update"]);
            $this->reviewsTableConnect->updateBy($updateArray, $this->getGetter->getField("id"));
            $this->adminCheck();
            $currentUrl = "/admin/reviewsCrud";
            header("location:" . $currentUrl);
            exit();
        }

        $this->templateBuilder([
            "adminLayout/header",
            "admin/reviewUpdate",
            "adminLayout/footer"
        ], [
            "reviewRow" => $this->reviewsTableConnect->findById($this->getGetter->getField("id")),
        ]);
    }

    public function actionReviewActivate(): never
    {
        $this->reviewsTableConnect->updateBy(["status" => 1], $this->getGetter->getField("id"));
        $this->adminCheck();
        $currentUrl = "/admin/reviewsCrud";
        header("location:" . $currentUrl);
        exit;
    }

    public function actionReviewDeactivate(): never
    {
        $this->reviewsTableConnect->updateBy(["status" => 0], $this->getGetter->getField("id"));
        $this->adminCheck();
        $currentUrl = "/admin/reviewsCrud";
        header("location:" . $currentUrl);
        exit;
    }

    public function actionReviewDelete(): never
    {
        $id = $this->getGetter->getField("id");
        $this->reviewsTableConnect->deleteById($id);

        $currentUrl = "/admin/reviewsCrud";
        header("location:" . $currentUrl);
        exit();
    }
}