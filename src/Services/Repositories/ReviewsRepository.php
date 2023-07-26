<?php

namespace Services\Repositories;

use Services\Pagination;

class ReviewsRepository extends BaseRepository
{
    protected string $tableName = 'reviews';
    public Pagination $pagination;
    private const COUNT = 5;

    public function __construct()
    {
        parent::__construct();
    }

    public function getPagination(): Pagination
    {
        return $this->pagination;
    }

    public function getPaginationRows(int $page = 1): array
    {

        $query = "SELECT * FROM " . $this->getTableName();
        $countQuery = $this->countQueryBy(["status" => "1"]);

        $this->pagination = new Pagination($query, $page, self::COUNT);
        $prepareQuery = $this->pagination->prepareQuery(["status" => "1"], ["create_time" => "DESC"]);

        $sumRows = $this->countRows($countQuery);
        $this->pagination->setSumRows($sumRows);

        $reviewsQuery = $this->pagination->buildQuery($prepareQuery);

        return $this->getRows($reviewsQuery);
    }

    public function getPaginationCrudRows(int $page = 1): array
    {

        $query = "SELECT * FROM " . $this->getTableName();
        $countQuery = $this->countQueryAll();

        $this->pagination = new Pagination($query, $page, self::COUNT);
        $prepareQuery = $this->pagination->prepareQuery([], ["status" => "DESC"]);

        $sumRows = $this->countRows($countQuery);
        $this->pagination->setSumRows($sumRows);

        $reviewsQuery = $this->pagination->buildQuery($prepareQuery);

        return $this->getRows($reviewsQuery);
    }

}