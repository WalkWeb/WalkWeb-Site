<?php

declare(strict_types=1);

namespace Migrations;

use WalkWeb\NW\AppException;
use WalkWeb\NW\MySQL\ConnectionPool;

class Version_2024_07_01_15_50_50_71
{
    /**
     * @param ConnectionPool $connectionPool
     * @throws AppException
     */
    public function run(ConnectionPool $connectionPool): void
    {
        $connectionPool->getConnection()->query('
            CREATE TABLE `accounts` (
                `id`             VARCHAR(36) PRIMARY KEY,                     # ID
                `login`          VARCHAR(20) UNIQUE NOT NULL,                 # Login
                `name`           VARCHAR(20) UNIQUE NOT NULL,                 # Отображаемое имя
                `password`       VARCHAR(60) NOT NULL,                        # хеш пароля
                `email`          VARCHAR(30) UNIQUE NOT NULL,                 # почта
                `email_verified` TINYINT NOT NULL DEFAULT 0,                  # подтвержден ли почтовый ящик
                `reg_complete`   TINYINT NOT NULL DEFAULT 0,                  # завершена ли регистрация
                `auth_token`     VARCHAR(30) NOT NULL,                        # токен для авторизации
                `verified_token` VARCHAR(30) NOT NULL,                        # токен для подтверждения действий
                `template`       VARCHAR(10) NOT NULL DEFAULT \'default\',    # используемый шаблон сайта
                `ip`             VARCHAR(16) NOT NULL,                        # ip при регистрации
                `ref`            VARCHAR(30) NOT NULL,                        # реферальный ключ
                `floor_id`       TINYINT UNSIGNED NOT NULL,                   # id пола
                `status_id`      TINYINT UNSIGNED NOT NULL DEFAULT 1,         # id статуса
                `group_id`       TINYINT UNSIGNED NOT NULL DEFAULT 1,         # id группы
                `energy_id`      VARCHAR(36) NOT NULL,                        # id записи в таблице `energy`
                `chat_status_id` TINYINT UNSIGNED NOT NULL DEFAULT 1,         # id статуса аккаунта в чате
                `upload`         INT UNSIGNED NOT NULL DEFAULT 0,             # суммарный вес файлов, загруженных пользователем
                `notice`         TINYINT UNSIGNED NOT NULL DEFAULT 0,         # есть ли у пользователя активные уведомления
                `user_agent`     VARCHAR(100) NOT NULL DEFAULT \'undefined\', # информация userAgent полученная от браузера
                `can_like`       TINYINT NOT NULL DEFAULT 1,                  # Может ли пользователь лайкать чужие посты/комментарии
                `created_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP,         # дата регистрации
                `updated_at`     TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    
                FOREIGN KEY (`floor_id`) REFERENCES `floors`(`id`),
                FOREIGN KEY (`status_id`) REFERENCES `account_status`(`id`),
                FOREIGN KEY (`group_id`) REFERENCES `account_group`(`id`),
                FOREIGN KEY (`energy_id`) REFERENCES `account_energy`(`id`),
                FOREIGN KEY (`chat_status_id`) REFERENCES `chat_status_account`(`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;
        
        ');

        echo "Added accounts\n";
    }
}
