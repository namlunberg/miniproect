<?php
namespace Controllers\Admin;

use Controllers\BaseController;
use Services\Pagination;
use Services\Security;
use Services\ServiceContainer;

class ReviewsCrudController extends BaseController
{
    protected Security $security;
    public function __construct()
    {
        parent::__construct();
        $this->security=ServiceContainer::getService("security");
        $this->connect->setTableName("users");

        if (!$this->security->isAuth()) {
            header("location:/?mode=auth");
        }


    }

    public function adminCheck(): void
    {
        $this->connect->setTableName("users");
        $adminDate = $this->connect->findOne(["sid"=>$this->coockieGetter->getField("sid")]);

        $this->connect->setTableName("reviews");
        $this->connect->updateBy(["adminId" => $adminDate["id"]], $this->getGetter->getField("id"));
    }

    public function actionReviews(): void
    {
        $this->connect->setTableName("reviews");

        $page = $this->request->getGet()->getField("page");
        if (empty($page)) {
            $page = 1;
        }

        $pagination = new Pagination($this->connect->getTableName(), ["status" => "ASC"], 5, $this->connect->countRows($this->connect->countQueryAll()), $page);

        $this->templateBuilder([
            "adminLayout/header",
            "admin/reviewsCrud",
            "adminLayout/footer"
        ], [
            "reviewsRows" => $this->connect->query($pagination->buildQuery()),
            "adminActivity" => $this->connect->joinSelect("users", ['login'], "id", "adminId"),
            "currentUrl" => "/?mode=admin&subModeAdmin=reviewsCrud",
        ], [
            "pageNumber" => $pagination->getPageNumber(),
            "sumPages" => $pagination->sumPages(),
        ]);
    }

    public function actionReviewUpdate(): void
    {
        $this->connect->setTableName("reviews");

        if (!empty($this->postGetter->getField("update"))){
            $updateArray = [];
            foreach ($this->postGetter->getAll() as $key => $value) {
                $updateArray[$key] = $value;
            }
            unset($updateArray["update"]);
            $this->connect->updateBy($updateArray, $this->getGetter->getField("id"));
            $this->adminCheck();
            $currentUrl = "?mode=admin&subModeAdmin=reviewsCrud";
            header("location:/" . $currentUrl);
            exit();
        }

        $this->templateBuilder([
            "adminLayout/header",
            "admin/reviewUpdate",
            "adminLayout/footer"
        ], [
            "reviewRow" => $this->connect->findById($this->getGetter->getField("id")),
        ]);
    }

    public function actionReviewActivate(): never
    {
        $this->connect->setTableName("reviews");
        $this->connect->updateBy(["status" => 1], $this->getGetter->getField("id"));
        $this->adminCheck();
        $currentUrl = "/?mode=admin&subModeAdmin=reviewsCrud";
        header("location:" . $currentUrl);
        exit;
    }

    public function actionReviewDeactivate(): never
    {
        $this->connect->setTableName("reviews");
        $this->connect->updateBy(["status" => 0], $this->getGetter->getField("id"));
        $this->adminCheck();
        $currentUrl = "/?mode=admin&subModeAdmin=reviewsCrud";
        header("location:" . $currentUrl);
        exit;
    }

    public function actionReviewDelete(): never
    {
        $id = $this->getGetter->getField("id");
        $this->connect->setTableName("reviews");
        $this->connect->deleteById($id);

        $currentUrl = "/?mode=admin&subModeAdmin=reviewsCrud";
        header("location:" . $currentUrl);
        exit();
    }
}