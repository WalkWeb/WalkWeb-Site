<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_05_14_22_04_48
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `notice_type` (
                `id`   TINYINT UNSIGNED PRIMARY KEY,
                `name` VARCHAR(10) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        $connectionPool->getConnection()->query("
            INSERT INTO `notice_type` (`id`, `name`) VALUES
            (1, 'Info'),
            (2, 'Warning'),
            (3, 'Success');
        ");

        $connectionPool->getConnection()->query('
            CREATE TABLE `notices` (
                `id`         VARCHAR(36) PRIMARY KEY,
                `type`       TINYINT NOT NULL DEFAULT 1,
                `account_id` VARCHAR(36) NOT NULL,
                `message`    VARCHAR(600) NOT NULL,
                `view`       TINYINT NOT NULL DEFAULT 0,
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        echo "Added notices\n";
    }
}
