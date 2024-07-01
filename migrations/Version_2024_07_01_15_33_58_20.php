<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_01_15_33_58_20
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `account_status` (
                `id`     TINYINT UNSIGNED PRIMARY KEY,
                `status` VARCHAR(20) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        $connectionPool->getConnection()->query("
            INSERT INTO `account_status`(`id`, `status`) VALUES
            (1, 'Активен'),
            (2, 'Заблокирован');
        ");

        echo "Added account_status\n";
    }
}
