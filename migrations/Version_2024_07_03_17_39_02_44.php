<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_03_17_39_02_44
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            ALTER TABLE `account_energy` ADD COLUMN `bonus` SMALLINT NOT NULL DEFAULT 0; # TODO На удаление, браться бонус будет из characters_main
        ');

        $connectionPool->getConnection()->query('
            ALTER TABLE `account_energy` CHANGE `time` `updated_at` DECIMAL(20,4) NOT NULL;
        ');

        echo "Updated account_energy table\n";
    }
}
