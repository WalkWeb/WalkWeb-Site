<?php

declare(strict_types=1);

namespace App\Domain\Account\Collection;

use App\Domain\Account\AccountException;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;

class AccountListRepository
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * TODO Подумать над тем, что можно пропускать юзеров не завершивших регистрацию и заблокированных
     *
     * TODO Добавить фильтрацию по theme
     *
     * @param int $offset
     * @param int $limit
     * @return AccountCollection
     * @throws AccountException
     * @throws AppException
     */
    public function getAll(int $offset, int $limit): AccountCollection
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT
                
                `accounts`.`id`,
                `accounts`.`name`,
                `accounts`.`status_id`,
                `accounts`.`group_id`,
                `characters_main`.`level`,
                `characters_main`.`exp`,
                `avatars`.`small_url` as `avatar`

                FROM `accounts` 

                JOIN `characters_main` on `accounts`.`id` = `characters_main`.`account_id`
                JOIN `characters` on `accounts`.`character_id` = `characters`.`id`
                JOIN `avatars` on `characters`.`avatar_id` = `avatars`.`id`

                ORDER BY `created_at` LIMIT ? OFFSET ?',
            [
                ['type' => 'i', 'value' => $limit],
                ['type' => 'i', 'value' => $offset],
            ]
        );

        // TODO Mock
        foreach ($data as &$datum) {
            $datum['carma'] = 0;
        }

        return AccountCollectionFactory::create($data);
    }

    /**
     * @return int
     * @throws AppException
     */
    public function getTotal(): int
    {
        return $this->container->getConnectionPool()->getConnection()->query(
            'SELECT count(`id`) as `total` FROM `accounts`', [], true
        )['total'];
    }
}
