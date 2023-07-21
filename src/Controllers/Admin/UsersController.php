<?php

namespace Controllers\Admin;

use Controllers\BaseController;
use Services\BaseRepository;
use Services\Security;
use Services\ServiceContainer;

class UsersController extends BaseController
{
    protected Security $security;
    protected BaseRepository $usersTableConnect;
    public function __construct()
    {
        parent::__construct();
        $this->security = ServiceContainer::getService("security");
        $this->usersTableConnect = ServiceContainer::getService("usersTableConnect");

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
            'usersRows' => $this->usersTableConnect->getRows("SELECT * FROM " . $this->usersTableConnect->getTableName()),
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
            $this->usersTableConnect->updateBy($updateArray, $this->getGetter->getField("id"));
            $currentUrl = "/admin";
            header("location:" . $currentUrl);
            exit();
        }

        $this->templateBuilder([
            "adminLayout/header",
            "admin/userUpdate",
            "adminLayout/footer"
        ], [
            "userRow" => $this->usersTableConnect->findById($this->getGetter->getField("id")),
        ]);
    }

    public function actionUserCreate(): void
    {
        if (!empty($this->postGetter->getField("create"))) {
            $currentUrl = $this->request->getCurrentUrl();

            $login = $this->postGetter->getField("login");
            $password = $this->postGetter->getField("password");
            $email = $this->postGetter->getField("email");
            $createTry = $this->usersTableConnect->findOne(["login"=>$login]);
            $mailCheck = ( (!empty($email)) ? $this->usersTableConnect->findOne(["email"=>$email]) : NULL );
            if (!empty($createTry)) {
                $this->sessionGetter->setField("loginCreate", "n");
            } elseif (!empty($mailCheck)) {
                $this->sessionGetter->setField("emailCreate", "n");
            } else {
                $password = password_hash($password, PASSWORD_BCRYPT);
                $this->usersTableConnect->insertBy(["login"=>$login, "password"=>$password, "email"=>$email]);

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
        $this->usersTableConnect->deleteById($id);

        $currentUrl = "/admin";
        header("location:" . $currentUrl);
        exit();
    }
}
