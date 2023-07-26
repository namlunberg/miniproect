<?php
namespace Controllers\Admin;

use Controllers\BaseController;
use Services\Pagination;
use Services\Repositories\UsersRepository;
use Services\Repositories\ReviewsRepository;
use Services\Security;
use Services\ServiceContainer;

class ReviewsCrudController extends BaseController
{
    protected Security $security;
    protected UsersRepository $usersRepository;
    protected ReviewsRepository $reviewsRepository;
    public function __construct()
    {
        parent::__construct();
        $this->security=ServiceContainer::getService("security");
        $this->usersRepository = ServiceContainer::getService("usersRepository");
        $this->reviewsRepository = ServiceContainer::getService("reviewsRepository");

        if (!$this->security->isAuth()) {
            header("location:/auth");
        }


    }

    public function adminCheck(): void
    {
        $adminDate = $this->usersRepository->findOne(["sid"=>$this->coockieGetter->getField("sid")]);

        $this->reviewsRepository->updateBy(["adminId" => $adminDate["id"]], $this->getGetter->getField("id"));
    }

    public function actionReviews(): void
    {
        $page = $this->request->getGet()->getField("page");
        if (empty($page)) {
            $page = 1;
        }


        $this->templateBuilder([
            "adminLayout/header",
            "admin/reviewsCrud",
            "adminLayout/footer"
        ], [
            "reviewsRows" => $this->reviewsRepository->getPaginationCrudRows($page),
            "adminActivity" => $this->reviewsRepository->joinSelect("users", ['login'], "id", "adminId"),
            "currentUrl" => "/admin/reviewsCrud",
        ], [
            "pageNumber" => $this->reviewsRepository->getPagination()->getPageNumber(),
            "sumPages" => $this->reviewsRepository->getPagination()->sumPages(),
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
            $this->reviewsRepository->updateBy($updateArray, $this->getGetter->getField("id"));
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
            "reviewRow" => $this->reviewsRepository->findById($this->getGetter->getField("id")),
        ]);
    }

    public function actionReviewActivate(): never
    {
        $this->reviewsRepository->updateBy(["status" => 1], $this->getGetter->getField("id"));
        $this->adminCheck();
        $currentUrl = "/admin/reviewsCrud";
        header("location:" . $currentUrl);
        exit;
    }

    public function actionReviewDeactivate(): never
    {
        $this->reviewsRepository->updateBy(["status" => 0], $this->getGetter->getField("id"));
        $this->adminCheck();
        $currentUrl = "/admin/reviewsCrud";
        header("location:" . $currentUrl);
        exit;
    }

    public function actionReviewDelete(): never
    {
        $id = $this->getGetter->getField("id");
        $this->reviewsRepository->deleteById($id);

        $currentUrl = "/admin/reviewsCrud";
        header("location:" . $currentUrl);
        exit();
    }
}