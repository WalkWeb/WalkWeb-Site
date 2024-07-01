<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_01_15_40_35_19
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `account_energy` (
                `id`      VARCHAR(36) PRIMARY KEY,
                `energy`  SMALLINT UNSIGNED NOT NULL,
                `time`    DECIMAL(20,4) NOT NULL,
                `residue` TINYINT UNSIGNED NOT NULL DEFAULT 0
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        echo "Added account_energy\n";
    }
}
