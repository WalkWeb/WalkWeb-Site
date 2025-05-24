<?php

declare(strict_types=1);

namespace App\Domain\Statistic;

use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;

class StatisticRepository
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * TODO Добавить фильтрацию по theme
     *
     * @return int
     * @throws AppException
     */
    public function getTotalUser(): int
    {
        return $this->container->getConnectionPool()->getConnection()->query(
            'SELECT count(`id`) as `count` FROM `accounts`',
            [],
            true
        )['count'] ?? 0;
    }

    /**
     * @return int
     * @throws AppException
     */
    public function getTotalPost(): int
    {
        return $this->container->getConnectionPool()->getConnection()->query(
            'SELECT count(`id`) as `count` FROM `posts`',
            [],
            true
        )['count'] ?? 0;
    }

    /**
     * @return int
     * @throws AppException
     */
    public function getTotalComments(): int
    {
        return $this->container->getConnectionPool()->getConnection()->query(
            'SELECT count(`id`) as `count` FROM `post_comments`',
            [],
            true
        )['count'] ?? 0;
    }

    /**
     * @return int
     * @throws AppException
     */
    public function getTotalTags(): int
    {
        return $this->container->getConnectionPool()->getConnection()->query(
            'SELECT count(`id`) as `count` FROM `post_tags`',
            [],
            true
        )['count'] ?? 0;
    }
}
