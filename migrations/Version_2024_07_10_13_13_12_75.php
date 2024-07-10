<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_10_13_13_12_75
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE INDEX `characters_main_account_era` ON `characters_main` (`account_id`, `era_id`);
        ');

        echo "Add unique index characters_main: account_id + era_id\n";
    }
}
