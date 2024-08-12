<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_08_12_14_54_32_72
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            ALTER TABLE `accounts` ADD `post_count` INT UNSIGNED NOT NULL DEFAULT 0;
        ');

        $connectionPool->getConnection()->query('
            ALTER TABLE `accounts` ADD `comment_count` INT UNSIGNED NOT NULL DEFAULT 0;
        ');

        echo "accounts: added post_count and comment_count column\n";
    }
}
