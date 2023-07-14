<?php

namespace Controllers;

use Services\BaseConnect;
use Services\Requests\Request;
use Services\Security;

class AdminController extends BaseController
{
    private Security $security;
    public function __construct(BaseConnect $connect, Request $request, Security $security)
    {
        parent::__construct($connect, $request);
        $this->security=$security;
        $this->connect->setTableName("users");
    }

    public function actionAdmin(): void
    {
        if (!$this->security->isAuth()) {
            header("location:/?mode=auth");
        }
        $this->templateBuilder([
            "layout/header",
            "admin/list",
            "layout/footer"
        ], [
            'currentUrl' => $this->request->getCurrentUrl(),
        ]);
    }
}