<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_08_20_10_43_33_35
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `account_carma` (
                `id`                  VARCHAR(36) PRIMARY KEY,
                `account_id`          VARCHAR(36) NOT NULL,
                `season_id`           TINYINT UNSIGNED NOT NULL,
                `carma`               INT NOT NULL DEFAULT 0,          -- Общее количество кармы
                `uses`                INT UNSIGNED NOT NULL DEFAULT 0, -- Количество использованной кармы
                
                FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`),
                                  
                UNIQUE KEY `account_season_carma` (`account_id`, `season_id`)
                                                   
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        echo "Added account_carma\n";
    }
}
