<?php

namespace Services\Repositories;

class UsersRepository extends BaseRepository
{
    protected string $tableName = 'users';

    public function __construct()
    {
        parent::__construct();
    }

}