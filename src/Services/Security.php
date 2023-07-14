<?php

namespace Services;

use Services\BaseConnect;
use Services\Requests\Request;

class Security
{
    private BaseConnect $connect;
    private Request $request;
    public function __construct(BaseConnect $connect, Request $request)
    {
        $this->connect = $connect;
        $this->request = $request;
    }

    public function isCurrentPass(string $passwordFromForm, string $passwordFromDb): bool
    {
        return password_verify($passwordFromForm, $passwordFromDb);
    }

    public function confirmAuth(array $authTry) :void
    {
        $sidValue = $this->sidGenerator();
        $this->request->getCookies()->createCookie("sid", "$sidValue", 1800);
        $this->connect->updateBy(["sid"=>$sidValue], $authTry["id"]);
    }

    public function isAuth(): bool
    {
        $sid = $this->request->getCookies()->getField("sid");
        $sidInBase = $this->connect->findOne(["sid"=>$sid]);
        if ($sidInBase) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    public function sidGenerator(): string
    {
        $sid = uniqid();
        $sidInBase = $this->connect->findBy(["sid" => $sid]);
        if (!empty($sidInBase)) {
            $sid = $this->sidGenerator();
        }
        return $sid;
    }
}