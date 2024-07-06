<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_06_21_23_46_83
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `era` (
                `id`     TINYINT UNSIGNED PRIMARY KEY,
                `name`   VARCHAR(10) NOT NULL,
                `actual` TINYINT NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        $connectionPool->getConnection()->query("
            INSERT INTO `era` (`id`, `name`, `actual`) VALUES
            (1, 'Alpha', 1),
            (2, 'Beta', 0);
        ");

        echo "Added era\n";
    }
}
