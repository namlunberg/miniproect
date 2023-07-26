<?php

namespace Controllers\Admin;

use Controllers\BaseController;
use Services\Repositories\usersRepository;
use Services\Security;
use Services\ServiceContainer;

class UsersController extends BaseController
{
    protected Security $security;
    protected usersRepository $usersRepository;
    public function __construct()
    {
        parent::__construct();
        $this->security = ServiceContainer::getService("security");
        $this->usersRepository = ServiceContainer::getService("usersRepository");

        if (!$this->security->isAuth()) {
            header("location:/auth");
        }
    }


    public function actionUsers(): void
    {
        $this->templateBuilder([
            "adminLayout/header",
            "admin/usersCrud",
            "adminLayout/footer"
        ], [
            'usersRows' => $this->usersRepository->getRows("SELECT * FROM " . $this->usersRepository->getTableName()),
            'get' => $this->request->getGet()->getAll(),
        ]);
    }

    public function actionUserUpdate(): void
    {
        if (!empty($this->postGetter->getField("update"))){
            $updateArray = [];
            foreach ($this->postGetter->getAll() as $key => $value) {
                $updateArray[$key] = $value;
                if (!empty($updateArray["password"]) && $key === "password") {
                    $updateArray["password"] = password_hash($updateArray["password"], PASSWORD_BCRYPT);
                } elseif (empty($updateArray["password"]) && $key === "password") {
                    unset($updateArray["password"]);
                }
            }
            unset($updateArray["update"]);
            $this->usersRepository->updateBy($updateArray, $this->getGetter->getField("id"));
            $currentUrl = "/admin";
            header("location:" . $currentUrl);
            exit();
        }

        $this->templateBuilder([
            "adminLayout/header",
            "admin/userUpdate",
            "adminLayout/footer"
        ], [
            "userRow" => $this->usersRepository->findById($this->getGetter->getField("id")),
        ]);
    }

    public function actionUserCreate(): void
    {
        if (!empty($this->postGetter->getField("create"))) {
            $currentUrl = $this->request->getCurrentUrl();

            $login = $this->postGetter->getField("login");
            $password = $this->postGetter->getField("password");
            $email = $this->postGetter->getField("email");
            $createTry = $this->usersRepository->findOne(["login"=>$login]);
            $mailCheck = ( (!empty($email)) ? $this->usersRepository->findOne(["email"=>$email]) : NULL );
            if (!empty($createTry)) {
                $this->sessionGetter->setField("loginCreate", "n");
            } elseif (!empty($mailCheck)) {
                $this->sessionGetter->setField("emailCreate", "n");
            } else {
                $password = password_hash($password, PASSWORD_BCRYPT);
                $this->usersRepository->insertBy(["login"=>$login, "password"=>$password, "email"=>$email]);

                $currentUrl = "/admin";
            }
            header("location:" . $currentUrl);
            exit;
        }

        $this->templateBuilder([
            "adminLayout/header",
            "admin/userCreate",
            "adminLayout/footer"
        ], [
            'sessionGetter' => $this->sessionGetter,
        ]);

        if ($this->sessionGetter->getField("loginCreate")) {
            $this->sessionGetter->removeField("loginCreate");
        }
        if ($this->sessionGetter->getField("emailCreate")) {
            $this->sessionGetter->removeField("emailCreate");
        }
    }

    public function actionUserDelete(): never
    {
        $id = $this->getGetter->getField("id");
        $this->usersRepository->deleteById($id);

        $currentUrl = "/admin";
        header("location:" . $currentUrl);
        exit();
    }
}
