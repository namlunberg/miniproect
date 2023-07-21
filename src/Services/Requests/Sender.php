<?php

namespace Services\Requests;

use Services\BaseConnect;

class Sender
{
    private BaseConnect $connect;
    public function __construct(BaseConnect $connect)
    {
        $this->connect=$connect;
    }

    public function sendMailToEachAdmin(): void
    {
        $this->connect->setTableName("users");
        $admins = $this->connect->findAll();
        foreach ($admins as $row) {
            if (!empty($row["email"])) {
                mail($row["email"], "Test", "Была создана новая запись");
            }
        }
    }
}