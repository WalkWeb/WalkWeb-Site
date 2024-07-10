<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_10_08_44_14_32
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            ALTER TABLE `accounts` ADD `main_character_id` VARCHAR(36);
        ');

        $connectionPool->getConnection()->query('
            ALTER TABLE `accounts` ADD CONSTRAINT `accounts_main_character_id` FOREIGN KEY (`main_character_id`) 
                REFERENCES `characters_main`(`id`);
        ');

        echo "Added accounts.main_character_id\n";
    }
}
