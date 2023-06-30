<?php

namespace src\classes;

use mysqli;

class Connect
{
    private string $hostName;
    private string $user;
    private string $password="";
    private string $baseName;
    private \mysqli $conn;

    public function getConnect() {
        return $this->conn;
    }

    public function __construct(string $hostName, string $user, string $baseName, string $password="")
    {
        $this->hostName = $hostName;
        $this->user = $user;
        $this->baseName = $baseName;
        $this->password = $password;
    }

    public function connect ():mysqli
    {
        $this->conn = new mysqli($this->hostName, $this->user, $this->password, $this->baseName);
        if ($this->conn->connect_error){
            die("Ошибка: " . $this->conn->connect_error);
        }
        return $this->conn;
    }

    public function query(string $query): bool|\mysqli_result
    {
        return $this->conn->query($query);
    }

    public function getRows(string $query): array
    {
        $result = [];

        $rows = $this->query($query);
        while($row = mysqli_fetch_assoc($rows)) {
            $result[] = $row;
        }

        return $result;
    }

}
