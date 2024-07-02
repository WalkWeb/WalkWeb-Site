<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_02_18_46_30_16
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            ALTER TABLE `accounts` ADD INDEX `accounts_auth_token` (`auth_token`);
        ');

        echo "Added index to accounts.auth_token\n";
    }
}
