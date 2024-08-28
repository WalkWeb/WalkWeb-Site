<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_08_28_09_02_39_87
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `lk_account_community` (
                `id`           VARCHAR(36) PRIMARY KEY,
                `account_id`   VARCHAR(36) NOT NULL,
                `community_id` VARCHAR(36) NOT NULL,
                `active`       INT NOT NULL DEFAULT 1,
                
                FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`),
                FOREIGN KEY (`community_id`) REFERENCES `communities`(`id`),
                                  
                UNIQUE KEY `account_community` (`account_id`, `community_id`)
                                                   
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        echo "Added lk_account_community\n";
    }
}
