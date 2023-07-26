<?php

namespace Services\Requests;

use Services\Repositories\usersRepository;
use Services\ServiceContainer;

class Sender
{
    private usersRepository $usersRepository;
    public function __construct()
    {
        $this->usersRepository = ServiceContainer::getService('usersRepository');
    }

    public function sendMailToEachAdmin(): void
    {
        $admins = $this->usersRepository->findAll();
        foreach ($admins as $row) {
            if (!empty($row["email"])) {
                mail($row["email"], "Test", "Была создана новая запись");
            }
        }
    }
}