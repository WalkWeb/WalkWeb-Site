<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_08_04_18_48_09_25
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `post_status` (
                `id`   TINYINT UNSIGNED PRIMARY KEY,
                `name` VARCHAR(20) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        $connectionPool->getConnection()->query("
            INSERT INTO `post_status`(`id`, `name`) VALUES
            (1, 'Default'),
            (2, 'Silver'),
            (3, 'Gold'),
            (4, 'Diamond');
        ");

        echo "Added post_status\n";
    }
}
