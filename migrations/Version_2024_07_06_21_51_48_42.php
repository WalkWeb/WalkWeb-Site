<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_06_21_51_48_42
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `characters_main` (
              `id`           VARCHAR(36) PRIMARY KEY,
              `account_id`   VARCHAR(36) NOT NULL,        # id основного аккаунта
              `era_id`       TINYINT UNSIGNED NOT NULL,   # id эры
              `level`        SMALLINT NOT NULL DEFAULT 1, # Уровень
              `exp`          INT NOT NULL DEFAULT 0,      # Опыт
              `energy_bonus` SMALLINT NOT NULL DEFAULT 0, # Бонус к энергии
              `upload_bonus` SMALLINT NOT NULL DEFAULT 0, # Бонус к месту на диске
              `stats_point`  INT NOT NULL DEFAULT 0,      # Свободных очков для распределения
            
              FOREIGN KEY (`account_id`) REFERENCES `accounts`(`id`),
              FOREIGN KEY (`era_id`) REFERENCES `era`(`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        echo "Added characters_main\n";
    }
}
