<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_08_04_19_06_40_43
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `post_tags` (
                `id`              VARCHAR(36) PRIMARY KEY,
                `name`            VARCHAR(30) NOT NULL,
                `slug`            VARCHAR(50) NOT NULL,
                `icon`            VARCHAR(60) NOT NULL,
                `preview_post_id` VARCHAR(36),
                `approved`        TINYINT UNSIGNED NOT NULL,
                `created_at`      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                
                FOREIGN KEY (`preview_post_id`) REFERENCES `posts`(`id`)  
                                     
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        echo "Added post_tags\n";
    }
}
