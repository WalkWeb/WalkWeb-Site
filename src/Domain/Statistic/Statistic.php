<?php

declare(strict_types=1);

namespace App\Domain\Statistic;

use WalkWeb\NW\AppException;

class Statistic implements StatisticInterface
{
    private StatisticRepository $repository;

    public function __construct(StatisticRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return int
     * @throws AppException
     */
    public function getTotalUser(): int
    {
        return $this->repository->getTotalUser();
    }
}
