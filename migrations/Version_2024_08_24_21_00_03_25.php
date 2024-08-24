<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_08_24_21_00_03_25
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('ALTER TABLE `posts` ADD COLUMN `rating` DOUBLE AS (`likes` - `dislikes`)');

        echo "Add posts.rating\n";
    }
}
