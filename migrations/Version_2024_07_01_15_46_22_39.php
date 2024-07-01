<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_01_15_46_22_39
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `chat_status_account` (
                `id`         TINYINT UNSIGNED PRIMARY KEY,
                `status`     VARCHAR(50) NOT NULL, # Название статуса 1 - Доступен, 2 - Только чтение, 3 - Доступ к чату закрыт, 4 - Модератор
                `channel_id` VARCHAR(36),          # Если статус модератора (4) - то здесь указывается номер канала, к которому даны модераторские права
                FOREIGN KEY (`channel_id`) REFERENCES `chat_channel`(`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        ');

        $connectionPool->getConnection()->query("
            INSERT INTO `chat_status_account` (`id`, `status`, `channel_id`) VALUES
            (10, 'Активен', NULL),
            (20, 'Только чтение', NULL),
            (30, 'Доступ закрыт', NULL),
            (40, 'Модератор', NULL),
            (50, 'Администратор', NULL);
        ");

        echo "Added chat_status_account\n";
    }
}
