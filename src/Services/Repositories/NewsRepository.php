<?php

namespace Services\Repositories;

class NewsRepository extends BaseRepository
{
    protected string $tableName = 'news';

    public function __construct()
    {
        parent::__construct();
    }
}