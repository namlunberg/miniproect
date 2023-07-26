<?php

namespace Services;

use Services\Repositories\UsersRepository;
use Services\Requests\Request;

class Security
{
    private Request $request;
    private UsersRepository $usersRepository;

    public function __construct()
    {
        $this->request = ServiceContainer::getService('request');
        $this->usersRepository = ServiceContainer::getService('usersRepository');
    }

    public function isCurrentPass(string $passwordFromForm, string $passwordFromDb): bool
    {
        return password_verify($passwordFromForm, $passwordFromDb);
    }

    public function sidGenerator(): string
    {
        $sid = uniqid();
        $sidInBase = $this->usersRepository->findBy(["sid" => $sid]);
        if (!empty($sidInBase)) {
            $sid = $this->sidGenerator();
        }
        return $sid;
    }

    public function confirmAuth(int $userId) :void
    {
        $sidValue = $this->sidGenerator();
        $this->request->getCookies()->createCookie("sid", $sidValue, 18000);
        $this->usersRepository->updateBy(["sid"=>$sidValue], $userId);
    }

    public function isAuth(): bool
    {
        $sid = $this->request->getCookies()->getField("sid");
        $sidInBase = $this->usersRepository->findOne(["sid"=>$sid]);
        if ($sidInBase) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    public function isAuthTableName(string $tableName): bool
    {
        $this->usersRepository->setTableName($tableName);
        $sid = $this->request->getCookies()->getField("sid");
        $sidInBase = $this->usersRepository->findOne(["sid"=>$sid]);
        if ($sidInBase) {
            $result = true;
        } else {
            $result = false;
        }
        return $result;
    }

    public function updateAuth(string $tableName): void
    {
        $this->usersRepository->setTableName($tableName);
        $userString = $this->usersRepository->findOne(["sid"=>$this->request->getCookies()->getField("sid")]);
        $this->confirmAuth($userString["id"]);
    }

}
