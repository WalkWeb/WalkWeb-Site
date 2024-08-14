<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_08_13_23_48_33_37
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `post_comments` (
                `id`         VARCHAR(36) PRIMARY KEY,       
                `post_id`    VARCHAR(36) NOT NULL,
                `author_id`  VARCHAR(36),      
                `guest_name` VARCHAR(50) NOT NULL DEFAULT \'\',
                `message`    TEXT NOT NULL,
                `approved`   TINYINT UNSIGNED NOT NULL,
                `parent_id`  VARCHAR(36),
                `level`      TINYINT UNSIGNED NOT NULL,
                `likes`      INT UNSIGNED NOT NULL,
                `dislikes`   INT UNSIGNED NOT NULL,    
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                
                FOREIGN KEY (`post_id`) REFERENCES `posts`(`id`),
                FOREIGN KEY (`author_id`) REFERENCES `accounts`(`id`),
                FOREIGN KEY (`parent_id`) REFERENCES `post_comments`(`id`)
                                  
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        echo "Added post_comments\n";
    }
}
