<?php

namespace Services;

use Services\Requests\Request;

class Router
{
    private array $routs=[];
    private Request $request;
    public function __construct()
    {
        $this->request = ServiceContainer::getService('request');
    }

    public function addRouts(array $routes): void
    {
        $this->routs = $routes;
    }

    public function getUri(): string
    {
        return $this->request->getServer()->getField("REQUEST_URI");
    }

    public function getRouts(): array
    {
        return $this->routs;
    }

    public function getClearUri(): string
    {
        $clearUri = explode("?", $this->getUri());
        return $clearUri[0];
    }

    public function run():void
    {
        $uri = $this->getClearUri();
        $routs = $this->getRouts();
        foreach ($routs as $rout => $value) {
            if (preg_match("~$uri~", $rout, $curRout)) {
                $controller = new $value['path'];
                $action = 'action'.ucfirst($value['action']);
                $controller->$action();
                break;
            }
        }

    }
}