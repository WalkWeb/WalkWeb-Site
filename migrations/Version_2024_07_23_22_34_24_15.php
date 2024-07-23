<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_23_22_34_24_15
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `seasons` (
                `id`   TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(20) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        $connectionPool->getConnection()->query("
            INSERT INTO `seasons`(`id`, `name`) VALUES
            (1, 'Season-1');
        ");

        echo "Added seasons\n";
    }
}
