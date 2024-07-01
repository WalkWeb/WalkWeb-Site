<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_01_15_42_40_56
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `chat_channel` (
                `id`   VARCHAR(36) PRIMARY KEY,  # id канала
                `name` VARCHAR(50) NOT NULL,     # Название канала
                `lvl`  TINYINT UNSIGNED NOT NULL # Минимальный уровень для доступа к каналу
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        echo "Added chat_channel\n";
    }
}
