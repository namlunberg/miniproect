<?php

namespace Controllers;

use Services\Security;
use Services\ServiceContainer;

class AuthController extends BaseController
{
    private Security $security;
    public function __construct()
    {
        parent::__construct();
        $connect = ServiceContainer::getService('connect');
        $request = ServiceContainer::getService('request');
        $this->security = new Security($this->connect, $this->request);
        $connect->setTableName("users");
    }

    public function actionAuth(): void
    {
        if (!empty($this->postGetter->getField("auth"))) {
            $currentUrl = $this->request->getCurrentUrl();

            $login = $this->postGetter->getField("login");
            $password = $this->postGetter->getField("password");
            $authTry = $this->connect->findOne(["login"=>$login]);
            if (empty($authTry)) {
                $this->sessionGetter->setField("loginAuth", "n");
            } else {
                $this->sessionGetter->setField("loginAuth", "y");
                $truePassword = $authTry["password"];
                $passwordVerify = $this->security->isCurrentPass($password, $truePassword);
                if (!$passwordVerify) {
                    $this->sessionGetter->setField("passwordAuth", "n");
                } else {
                    $userId = $authTry["id"];
                    $this->security->confirmAuth($userId);

                    $currentUrl = "/?mode=admin";
                }
            }
            header("location:" . $currentUrl);
            exit;
        }

        $this->templateBuilder([
            "layout/header",
            "auth/list",
            "layout/footer"
        ], [
            'sessionGetter' => $this->sessionGetter,
        ]);

        if ($this->sessionGetter->getField("loginAuth")) {
            $this->sessionGetter->removeField("loginAuth");
        }
        if ($this->sessionGetter->getField("passwordAuth")) {
            $this->sessionGetter->removeField("passwordAuth");
        }
    }
}