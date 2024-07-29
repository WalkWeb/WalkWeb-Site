<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_23_21_10_22_12
{
    /**
     * TODO Подумать над добавлением параметров: 1) small icon 2) playlable
     *
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `genesis` (
                `id`       TINYINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                `theme_id` TINYINT UNSIGNED NOT NULL,
                `icon`     VARCHAR(50) NOT NULL,
                `plural`   VARCHAR(50) NOT NULL,
                `single`   VARCHAR(50) NOT NULL,
                FOREIGN KEY (`theme_id`) REFERENCES `theme`(`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        $connectionPool->getConnection()->query("
            INSERT INTO `genesis`(`id`, `theme_id`, `icon`, `plural`, `single`) VALUES
            (1, 1, '/img/icon/genesis_default.png', 'Analysts', 'Analyst'),
            (2, 1, '/img/icon/genesis_default.png', 'Designers', 'Designer'),
            (3, 1, '/img/icon/genesis_default.png', 'Devops', 'Devops'),
            (4, 1, '/img/icon/genesis_default.png', 'Trainees', 'Intern'),
            (5, 1, '/img/icon/genesis_default.png', 'Programmers', 'Programmer'),
            (6, 1, '/img/icon/genesis_default.png', 'Managers', 'Manager');
        ");

        echo "Added genesis\n";
    }
}
