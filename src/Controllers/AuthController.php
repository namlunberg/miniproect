<?php

namespace Controllers;

use Services\BaseRepository;
use Services\Security;
use Services\ServiceContainer;

class AuthController extends BaseController
{
    private Security $security;
    private BaseRepository $usersTableConnect;
    public function __construct()
    {
        parent::__construct();
        $this->security = new Security();
        $this->usersTableConnect = ServiceContainer::getService("usersTableConnect");
    }

    public function actionAuth(): void
    {
        if (!empty($this->postGetter->getField("auth"))) {
            $currentUrl = $this->request->getCurrentUrl();

            $login = $this->postGetter->getField("login");
            $password = $this->postGetter->getField("password");
            $authTry = $this->usersTableConnect->findOne(["login"=>$login]);
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

                    $currentUrl = "/admin";
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