<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use App\Domain\Account\AccountException;
use Exception;
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
            
            `account_energy`.`id` as `energy_id`,
            `account_energy`.`energy` as `energy`,
            `account_energy`.`bonus` as `energy_bonus`,
            `account_energy`.`updated_at` as `energy_updated_at`,
            `account_energy`.`residue` as `energy_residue`

            FROM `accounts`
                
            JOIN `account_energy` on `accounts`.`energy_id` = `account_energy`.`id`

            WHERE `accounts`.`auth_token` = ?',
            [['type' => 's', 'value' => $authToken]],
            true
        );

        if (!$data) {
            throw new AppException(AccountException::NOT_FOUND, Response::NOT_FOUND);
        }

        $data['energy'] = [
            'energy_id'         => $data['energy_id'],
            'energy'            => $data['energy'],
            'energy_bonus'      => $data['energy_bonus'],
            'energy_updated_at' => (float)$data['energy_updated_at'],
            'energy_residue'    => $data['energy_residue'],
        ];

        $data['notices'] = $this->getNotice($data['notice'] === 1, $data['id']);

        // TODO Mocks
        $data['avatar'] = '';
        $data['level'] = 1;
        $data['stat_points'] = 0;

        return AuthFactory::create($data);
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
