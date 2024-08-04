<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_08_04_19_12_14_20
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `lk_post_tag` (
                `id`      VARCHAR(36) PRIMARY KEY,
                `post_id` VARCHAR(36) NOT NULL,
                `tag_id`  VARCHAR(36) NOT NULL,
                
                FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`),
                FOREIGN KEY (`tag_id`) REFERENCES `post_tags`(`id`)

            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        echo "Added lk_post_tag\n";
    }
}
