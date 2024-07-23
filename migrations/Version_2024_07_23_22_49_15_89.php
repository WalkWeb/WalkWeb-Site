<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_23_22_49_15_89
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `avatars` (
                `id`                VARCHAR(36) PRIMARY KEY,
                `character_main_id` VARCHAR(36) NOT NULL,
                `season_id`         TINYINT UNSIGNED NOT NULL,
                `genesis_id`        TINYINT UNSIGNED NOT NULL,
                `profession_id`     MEDIUMINT UNSIGNED NOT NULL,
                `avatar_id`         MEDIUMINT UNSIGNED NOT NULL,
                `floor_id`          TINYINT UNSIGNED NOT NULL,
                `level`             SMALLINT UNSIGNED NOT NULL DEFAULT 1,
                `exp`               MEDIUMINT UNSIGNED NOT NULL DEFAULT 0,
                `stats_point`       SMALLINT UNSIGNED NOT NULL DEFAULT 0,
                `skill_point`       SMALLINT UNSIGNED NOT NULL DEFAULT 0,
                
                FOREIGN KEY (`character_main_id`) REFERENCES `characters_main`(`id`),
                FOREIGN KEY (`season_id`) REFERENCES `seasons`(`id`),
                FOREIGN KEY (`genesis_id`) REFERENCES `genesis`(`id`),
                FOREIGN KEY (`profession_id`) REFERENCES `professions`(`id`),
                FOREIGN KEY (`avatar_id`) REFERENCES `avatars`(`id`),
                FOREIGN KEY (`floor_id`) REFERENCES `floors`(`id`)

            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        echo "Added characters\n";
    }
}
