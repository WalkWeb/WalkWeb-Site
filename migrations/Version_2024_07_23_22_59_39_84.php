<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_23_22_59_39_84
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            ALTER TABLE `accounts` ADD `character_id` VARCHAR(36);
        ');

        $connectionPool->getConnection()->query('
            ALTER TABLE `accounts` ADD CONSTRAINT `accounts_character_id` FOREIGN KEY (`character_id`) 
                REFERENCES `characters`(`id`);
        ');

        echo "Added accounts.character_id\n";
    }
}
