<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_08_07_10_21_49_79
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `lk_account_like_post` (
                `id`         VARCHAR(36) PRIMARY KEY,
                `account_id` VARCHAR(36) NOT NULL,
                `post_id`    VARCHAR(36) NOT NULL,
                `value`      TINYINT NOT NULL,
                
                FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`),
                FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`),

                UNIQUE KEY `like_account_post` (`account_id`, `post_id`)
                                                
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        echo "Added lk_account_like_post\n";
    }
}
