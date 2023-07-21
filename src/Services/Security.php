<?php

namespace Services;

use Services\Requests\Request;

class Security
{
    private Request $request;
    private BaseRepository $usersTableConnect;

    public function __construct()
    {
        $this->request = ServiceContainer::getService('request');
        $this->usersTableConnect = ServiceContainer::getService('usersTableConnect');
    }

    public function isCurrentPass(string $passwordFromForm, string $passwordFromDb): bool
    {
        return password_verify($passwordFromForm, $passwordFromDb);
    }

    public function sidGenerator(): string
    {
        $sid = uniqid();
        $sidInBase = $this->usersTableConnect->findBy(["sid" => $sid]);
        if (!empty($sidInBase)) {
            $sid = $this->sidGenerator();
        }
        return $sid;
    }

    public function confirmAuth(int $userId) :void
    {
        $sidValue = $this->sidGenerator();
        $this->request->getCookies()->createCookie("sid", $sidValue, 18000);
        $this->usersTableConnect->updateBy(["sid"=>$sidValue], $userId);
    }

    public function isAuth(): bool
    {
        $sid = $this->request->getCookies()->getField("sid");
        $sidInBase = $this->usersTableConnect->findOne(["sid"=>$sid]);
        if ($sidInBase) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    public function isAuthTableName(string $tableName): bool
    {
        $this->usersTableConnect->setTableName($tableName);
        $sid = $this->request->getCookies()->getField("sid");
        $sidInBase = $this->usersTableConnect->findOne(["sid"=>$sid]);
        if ($sidInBase) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    public function updateAuth(string $tableName): void
    {
        $this->usersTableConnect->setTableName($tableName);
        $userString = $this->usersTableConnect->findOne(["sid"=>$this->request->getCookies()->getField("sid")]);
        $this->confirmAuth($userString["id"]);
    }

}
