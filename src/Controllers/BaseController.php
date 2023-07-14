<?php

namespace Controllers;

use Services\BaseConnect;
use Services\Requests\Post;
use Services\Requests\Request;
use Services\Requests\Server;
use Services\Requests\Session;

abstract class BaseController
{
    protected BaseConnect $connect;
    protected Request $request;
    protected Post $postGetter;
    protected Session $sessionGetter;
    protected Server $serverGetter;

    public function __construct(BaseConnect $connect, Request $request)
    {
        $this->connect = $connect;
        $this->request = $request;
        $this->postGetter = $this->request->getPost();
        $this->sessionGetter = $this->request->getSession();
        $this->serverGetter = $this->request->getServer();
    }

    public function templateBuilder(array $templateNames,  array $dateArray=[], array $navArray = []): void
    {
        if (!empty($navArray)) {
            extract($navArray);
        }
        if (!empty($dateArray)) {
            extract($dateArray);
        }
        foreach ($templateNames as $value) {
            include $this->serverGetter->getField('DOCUMENT_ROOT') . "/templates/{$value}.php";
        }
    }


}