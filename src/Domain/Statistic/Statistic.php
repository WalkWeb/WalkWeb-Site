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
    public function getTotalUsers(): int
    {
        return $this->repository->getTotalUser();
    }

    /**
     * @return int
     * @throws AppException
     */
    public function getTotalPosts(): int
    {
        return $this->repository->getTotalPost();
    }

    /**
     * @return int
     * @throws AppException
     */
    public function getTotalComments(): int
    {
        return $this->repository->getTotalComments();
    }

    /**
     * @return int
     * @throws AppException
     */
    public function getTotalTags(): int
    {
        return $this->repository->getTotalTags();
    }
}
