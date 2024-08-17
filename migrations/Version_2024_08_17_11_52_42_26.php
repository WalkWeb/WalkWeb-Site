<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_08_17_11_52_42_26
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `lk_account_like_comment` (
                `id`         VARCHAR(36) PRIMARY KEY,
                `account_id` VARCHAR(36) NOT NULL,
                `comment_id` VARCHAR(36) NOT NULL,
                `value`      TINYINT NOT NULL,
                
                FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`),
                FOREIGN KEY (`comment_id`) REFERENCES `post_comments`(`id`),

                UNIQUE KEY `like_account_comment` (`account_id`, `comment_id`)
                                                
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        echo "Added lk_account_like_comment\n";
    }
}
