<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_23_21_07_29_10
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `theme` (
                `id`   TINYINT UNSIGNED PRIMARY KEY,
                `name` VARCHAR(50) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        $connectionPool->getConnection()->query("
            INSERT INTO `theme`(`id`, `name`) VALUES
            (1, 'it'),
            (2, 'game'),
            (3, 'video');
        ");

        echo "Added theme\n";
    }
}
