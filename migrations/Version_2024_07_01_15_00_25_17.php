<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_01_15_00_25_17
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `floors` (
                `id`     TINYINT UNSIGNED PRIMARY KEY,
                `plural` VARCHAR(50) NOT NULL,
                `single` VARCHAR(50) NOT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        $connectionPool->getConnection()->query("
            INSERT INTO `floors`(`id`, `plural`, `single`) VALUES
            (1, 'Мужской', 'Мужчина'),
            (2, 'Женский', 'Женщина');
        ");

        echo "Added floors\n";
    }
}
