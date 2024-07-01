<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_01_15_39_18_96
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `account_group` (
                `id`     TINYINT UNSIGNED PRIMARY KEY,
                `plural` VARCHAR(30) NOT NULL,
                `single` VARCHAR(30) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        $connectionPool->getConnection()->query("
            INSERT INTO `account_group`(`id`, `plural`, `single`) VALUES
            (10, 'Пользователи', 'Пользователь'),
            (20, 'Модераторы', 'Модератор'),
            (30, 'Главные администраторы', 'Главный администратор'),
            (31, 'Администраторы', 'Администратор');
        ");

        echo "Added account_group\n";
    }
}
