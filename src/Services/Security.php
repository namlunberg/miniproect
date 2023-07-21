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

    public function sidGenerator(): string
    {
        $sid = uniqid();
        $sidInBase = $this->connect->findBy(["sid" => $sid]);
        if (!empty($sidInBase)) {
            $sid = $this->sidGenerator();
        }
        return $sid;
    }

    public function confirmAuth(int $userId) :void
    {
        $sidValue = $this->sidGenerator();
        $this->request->getCookies()->createCookie("sid", $sidValue, 18000);
        $this->connect->updateBy(["sid"=>$sidValue], $userId);
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

    public function isAuthTableName(string $tableName): bool
    {
        $this->connect->setTableName($tableName);
        $sid = $this->request->getCookies()->getField("sid");
        $sidInBase = $this->connect->findOne(["sid"=>$sid]);
        if ($sidInBase) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    public function updateAuth(string $tableName): void
    {
        $this->connect->setTableName($tableName);
        $userString = $this->connect->findOne(["sid"=>$this->request->getCookies()->getField("sid")]);
        $this->confirmAuth($userString["id"]);
    }

}
