<?php

namespace Controllers;

use Services\BaseConnect;
use Services\Requests\Get;
use Services\Requests\Post;
use Services\Requests\Request;
use Services\Requests\Server;
use Services\Requests\Session;
use Services\ServiceContainer;

abstract class BaseController
{
    protected BaseConnect $connect;
    protected Request $request;
    protected Post $postGetter;
    protected Session $sessionGetter;
    protected Server $serverGetter;
    protected Get $getGetter;

    public function __construct()
    {
        $this->connect = ServiceContainer::getService('connect');;
        $this->request = ServiceContainer::getService('request');
        $this->postGetter = $this->request->getPost();
        $this->sessionGetter = $this->request->getSession();
        $this->serverGetter = $this->request->getServer();
        $this->getGetter = $this->request->getGet();
        $this->coockieGetter = $this->request->getCookies();
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