<?php

namespace Controllers;

use Services\BaseConnect;
use Services\Requests\Request;
use Services\Security;

class AuthController extends BaseController
{
    private Security $security;
    public function __construct(BaseConnect $connect, Request $request)
    {
        parent::__construct($connect, $request);
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
                    $this->security->confirmAuth($authTry);

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