<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_08_17_21_51_20_94
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `communities` (
                `id`                  VARCHAR(36) PRIMARY KEY,
                `level`               TINYINT UNSIGNED NOT NULL DEFAULT 1,
                `name`                VARCHAR(50) NOT NULL,
                `slug`                VARCHAR(70) NOT NULL,
                `description`         VARCHAR(255) NOT NULL DEFAULT \'\',
                `icon`                VARCHAR(100) NOT NULL DEFAULT \'\',
                `icon_small`          VARCHAR(100) NOT NULL DEFAULT \'\',
                `head_image`          VARCHAR(100) NOT NULL DEFAULT \'\',
                `followers`           INT UNSIGNED NOT NULL DEFAULT 1,
                `fixed_post_id`       VARCHAR(36),
                `menu`                VARCHAR(100),
                `owner_id`            VARCHAR(36) NOT NULL,
                `total_post_count`    INT UNSIGNED NOT NULL DEFAULT 0,
                `silver_post_count`   INT UNSIGNED NOT NULL DEFAULT 0,
                `gold_post_count`     INT UNSIGNED NOT NULL DEFAULT 0,
                `diamond_post_count`  INT UNSIGNED NOT NULL DEFAULT 0,
                `total_comment_count` BIGINT UNSIGNED NOT NULL DEFAULT 0,
                `created_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at`          TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                
                FOREIGN KEY (`owner_id`) REFERENCES `accounts`(`id`),
                FOREIGN KEY (`fixed_post_id`) REFERENCES `posts`(`id`)
                                                
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        echo "Added communities\n";
    }
}
