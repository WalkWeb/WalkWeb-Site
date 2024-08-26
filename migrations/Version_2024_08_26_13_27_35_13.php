<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_08_26_13_27_35_13
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query(
            'ALTER TABLE `posts` ADD COLUMN `community_id` VARCHAR(36) DEFAULT NULL'
        );

        $connectionPool->getConnection()->query(
            'ALTER TABLE `posts` ADD CONSTRAINT `posts_community_id` FOREIGN KEY (`community_id`)  REFERENCES `communities`(`id`)'
        );

        echo "Added posts.community_id\n";
    }
}
