<?php

namespace Services\Requests;

use Services\BaseRepository;
use Services\ServiceContainer;

class Sender
{
    private BaseRepository $usersTableConnect;
    public function __construct()
    {
        $this->usersTableConnect = ServiceContainer::getService('usersTableConnect');
    }

    public function sendMailToEachAdmin(): void
    {
        $admins = $this->usersTableConnect->findAll();
        foreach ($admins as $row) {
            if (!empty($row["email"])) {
                mail($row["email"], "Test", "Была создана новая запись");
            }
        }
    }
}