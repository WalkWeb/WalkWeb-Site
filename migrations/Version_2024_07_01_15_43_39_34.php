<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_01_15_43_39_34
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `chat_status_message` (
                `id`     TINYINT UNSIGNED PRIMARY KEY,
                `status` VARCHAR(50) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        $connectionPool->getConnection()->query("
            INSERT INTO `chat_status_message` (`id`, `status`) VALUES
            (1, 'Обычное'),
            (2, 'Удаленное'),
            (3, 'Объявление'),
            (4, 'Глобальное объявление');
        ");

        echo "Added chat_status_message\n";
    }
}
