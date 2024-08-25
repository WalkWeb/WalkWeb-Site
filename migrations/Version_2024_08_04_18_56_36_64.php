<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_08_04_18_56_36_64
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `posts` (
                `id`             VARCHAR(36) PRIMARY KEY,
                `author_id`      VARCHAR(36) NOT NULL,
                `title`          VARCHAR(255) NOT NULL,
                `slug`           VARCHAR(255) NOT NULL,
                `content`        TEXT NOT NULL,
                `html_content`   TEXT NOT NULL,
                `status_id`      TINYINT UNSIGNED NOT NULL,
                `likes`          INT NOT NULL DEFAULT 0,
                `dislikes`       INT NOT NULL DEFAULT 0,
                `comments_count` INT UNSIGNED NOT NULL DEFAULT 0,
                `published`      TINYINT UNSIGNED NOT NULL,
                `approved`       TINYINT UNSIGNED NOT NULL,
                `moderated`      TINYINT UNSIGNED NOT NULL,
                `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                
                FOREIGN KEY (`author_id`) REFERENCES `accounts`(`id`),
                FOREIGN KEY (`status_id`) REFERENCES `post_status`(`id`)

            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        echo "Added posts\n";
    }
}
