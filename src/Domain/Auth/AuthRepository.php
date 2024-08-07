<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use App\Domain\Account\Notice\Action\SendNoticeActionInterface;
use App\Domain\Account\Notice\NoticeCollection;
use App\Domain\Account\Notice\NoticeRepository;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;

class AuthRepository
{
    private Container $container;
    private NoticeRepository $noticeRepository;

    public function __construct(Container $container, NoticeRepository $noticeRepository = null)
    {
        $this->container = $container;
        $this->noticeRepository = $noticeRepository ?? new NoticeRepository($container);
    }

    /**
     * @param string $authToken
     * @param SendNoticeActionInterface $sendNoticeAction
     * @return AuthInterface|null
     * @throws AppException
     */
    public function get(string $authToken, SendNoticeActionInterface $sendNoticeAction): ?AuthInterface
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT 
 
            `accounts`.`id`, 
            `accounts`.`name`, 
            `accounts`.`verified_token`, 
            `accounts`.`group_id` as `account_group_id`, 
            `accounts`.`status_id` as `account_status_id`, 
            `accounts`.`can_like`,
            `accounts`.`notice`,
            `accounts`.`template`,
            `accounts`.`email_verified`,
            `accounts`.`upload`,
            
            `characters_main`.`id` as `main_character_id`,
            `characters_main`.`level`,
            `characters_main`.`exp`,
            `characters_main`.`upload_bonus`,
            `characters_main`.`stats_point` as `stat_points`,
       
            `account_energy`.`id` as `energy_id`,
            `account_energy`.`energy` as `energy`,
            `account_energy`.`bonus` as `energy_bonus`,
            `account_energy`.`updated_at` as `energy_updated_at`,
            `account_energy`.`residue` as `energy_residue`,

            `avatars`.`origin_url` as `avatar`

            FROM `accounts`
                
            LEFT JOIN `account_energy` ON `accounts`.`energy_id` = `account_energy`.`id`
            LEFT JOIN `characters_main` ON `accounts`.`id` = `characters_main`.`account_id`
            LEFT JOIN `characters` ON `accounts`.`character_id` = `characters`.`id`
            LEFT JOIN `avatars` ON `characters`.`avatar_id` = `avatars`.`id`

            WHERE `accounts`.`auth_token` = ?',
            [['type' => 's', 'value' => $authToken]],
            true
        );

        if (!$data) {
            return null;
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
            'character_id'          => $data['main_character_id'],
            'character_level'       => $data['level'],
            'character_exp'         => $data['exp'],
            'character_stat_points' => $data['stat_points'],
        ];

        $data['level'] = $level;

        $notices = $this->getNotice($data['notice'] === 1, $data['id']);

        return AuthFactory::create($data, $sendNoticeAction, $notices);
    }

    /**
     * @param bool $exist
     * @param string $accountId
     * @return NoticeCollection
     * @throws AppException
     */
    public function getNotice(bool $exist, string $accountId): NoticeCollection
    {
        if ($exist) {
            return $this->noticeRepository->getActual($accountId);
        }

        return new NoticeCollection();
    }

    /**
     * @param AuthInterface $account
     * @throws AppException
     */
    public function saveVerified(AuthInterface $account): void
    {
        $this->container->getConnectionPool()->getConnection()->query(
            'UPDATE `accounts` SET `email_verified` = ?, `reg_complete` = ? WHERE `id` = ?',
            [
                ['type' => 'i', 'value' => (int)$account->isEmailVerified()],
                ['type' => 'i', 'value' => 1], // TODO
                ['type' => 's', 'value' => $account->getId()],
            ],
        );
    }
}
