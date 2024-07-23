<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_23_21_21_57_24
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `professions` (
                `id`          MEDIUMINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `genesis_id`  TINYINT UNSIGNED NOT NULL,
                `icon`        VARCHAR(50) NOT NULL,
                `name_male`   VARCHAR(25) NOT NULL,
                `name_female` VARCHAR(25) NOT NULL,
                FOREIGN KEY (`genesis_id`) REFERENCES `genesis`(`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        $connectionPool->getConnection()->query("
            INSERT INTO `professions`(`id`, `genesis_id`, `icon`, `name_male`, `name_female`) VALUES
            (1, 1, '/img/icon/genesis_default.png', 'Default', 'Default'),
            (2, 2, '/img/icon/genesis_default.png', 'Default', 'Default'),
            (3, 3, '/img/icon/genesis_default.png', 'Default', 'Default'),
            (4, 4, '/img/icon/genesis_default.png', 'Default', 'Default'),
            (5, 5, '/img/icon/genesis_default.png', 'Default', 'Default'),
            (6, 6, '/img/icon/genesis_default.png', 'Default', 'Default');
        ");

        echo "Added professions\n";
    }
}
