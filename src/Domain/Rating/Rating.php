<?php

declare(strict_types=1);

namespace App\Domain\Rating;

use App\Domain\Account\Collection\AccountCollection;
use WalkWeb\NW\AppException;

class Rating implements RatingInterface
{
    private RatingRepository $repository;

    public function __construct(RatingRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return AccountCollection
     * @throws AppException
     */
    public function getTopAccountLevel(): AccountCollection
    {
        return $this->repository->getTopAccountLevel();
    }

    /**
     * @return AccountCollection
     * @throws AppException
     */
    public function getTopAccountCarma(): AccountCollection
    {
        return $this->repository->getTopAccountCarma();
    }
}
