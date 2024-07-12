<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use App\Domain\Account\AccountException;
use App\Domain\Account\Notice\Action\SendNoticeAction;
use App\Domain\Account\Notice\NoticeRepository;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Response;

class AuthRepository
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $authToken
     * @return AuthInterface|null
     * @throws AppException
     */
    public function get(string $authToken): ?AuthInterface
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT 
 
            `accounts`.`id`, 
            `accounts`.`name`, 
            `accounts`.`group_id` as `account_group_id`, 
            `accounts`.`status_id` as `account_status_id`, 
            `accounts`.`can_like`,
            `accounts`.`notice`,
            `accounts`.`template`,
            `accounts`.`email_verified`,
            
            `characters_main`.`id` as `character_id`,
            `characters_main`.`level`,
            `characters_main`.`exp`,
            `characters_main`.`stats_point` as `stat_points`,
       
            `account_energy`.`id` as `energy_id`,
            `account_energy`.`energy` as `energy`,
            `account_energy`.`bonus` as `energy_bonus`,
            `account_energy`.`updated_at` as `energy_updated_at`,
            `account_energy`.`residue` as `energy_residue`

            FROM `accounts`
                
            JOIN `account_energy` on `accounts`.`energy_id` = `account_energy`.`id`
            JOIN `characters_main` on `accounts`.`id` = `characters_main`.`account_id`

            WHERE `accounts`.`auth_token` = ?',
            [['type' => 's', 'value' => $authToken]],
            true
        );

        if (!$data) {
            throw new AppException(AccountException::NOT_FOUND, Response::NOT_FOUND);
        }

        // TODO Переименовать все параметры так, чтобы можно было просто кидать весь массив и все

        $data['energy'] = [
            'energy_id'         => $data['energy_id'],
            'energy'            => $data['energy'],
            'energy_bonus'      => $data['energy_bonus'],
            'energy_updated_at' => (float)$data['energy_updated_at'],
            'energy_residue'    => $data['energy_residue'],
        ];

        $level = [
            'account_id'            => $data['id'],
            'character_id'          => $data['character_id'],
            'character_level'       => $data['level'],
            'character_exp'         => $data['exp'],
            'character_stat_points' => $data['stat_points'],
        ];

        $data['level'] = $level;

        $data['notices'] = $this->getNotice($data['notice'] === 1, $data['id']);

        // TODO Mocks
        $data['avatar'] = '';

        return AuthFactory::create($data, new SendNoticeAction(new NoticeRepository($this->container)));
    }

    /**
     * @param bool $exist
     * @param string $accountId
     * @return array
     * @throws AppException
     */
    public function getNotice(bool $exist, string $accountId): array
    {
        if ($exist) {
            return $this->container->getConnectionPool()->getConnection()->query(
                'SELECT `id`, `type`, `account_id`, `message`, `view`, `created_at` 
                FROM `notices` WHERE `account_id` = ? AND `view` = 0',
                [['type' => 's', 'value' => $accountId]]
            );
        }

        return [];
    }
}
