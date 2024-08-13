<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_08_13_00_00_03_57
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `images` (
                `id`         VARCHAR(36) PRIMARY KEY,             
                `account_id` VARCHAR(36) NOT NULL,               
                `name`       VARCHAR(40) NOT NULL,                
                `dir`        VARCHAR(100),                        
                `size`       INT UNSIGNED NOT NULL,               
                `width`      MEDIUMINT UNSIGNED NOT NULL,         
                `height`     MEDIUMINT UNSIGNED NOT NULL,        
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                
                FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`)
                                  
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        echo "Added images\n";
    }
}
