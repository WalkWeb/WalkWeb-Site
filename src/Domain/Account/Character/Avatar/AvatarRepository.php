<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Avatar;

use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;

class AvatarRepository
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param int $id
     * @return AvatarInterface|null
     * @throws AppException
     */
    public function get(int $id): ?AvatarInterface
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT 
                   
            `avatars`.`id` as `avatar_id`,
            `avatars`.`floor_id`,
            `avatars`.`origin_url`,
            `avatars`.`small_url`,
            `genesis`.`id` as `genesis_id`,
            `genesis`.`theme_id`,
            `genesis`.`icon` as `genesis_icon`,
            `genesis`.`plural` as `genesis_plural`,
            `genesis`.`single` as `genesis_single`
            
            FROM `avatars` 
            
            JOIN `genesis` ON `avatars`.`genesis_id` = `genesis`.`id`
            
            WHERE `avatars`.`id` = ?',
            [['type' => 'i', 'value' => $id]],
            true
        );

        if (!$data) {
            return null;
        }

        return AvatarFactory::create($data);
    }

    /**
     * @param int $id
     * @param int $genesisId
     * @param int $floorId
     * @return AvatarInterface|null
     * @throws AppException
     */
    public function getForRegister(int $id, int $genesisId, int $floorId): ?AvatarInterface
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT 
                   
            `avatars`.`id` as `avatar_id`,
            `avatars`.`floor_id`,
            `avatars`.`origin_url`,
            `avatars`.`small_url`,
            `genesis`.`id` as `genesis_id`,
            `genesis`.`theme_id`,
            `genesis`.`icon` as `genesis_icon`,
            `genesis`.`plural` as `genesis_plural`,
            `genesis`.`single` as `genesis_single`
            
            FROM `avatars` 
            
            JOIN `genesis` ON `avatars`.`genesis_id` = `genesis`.`id`
            
            WHERE `avatars`.`id` = ? AND `avatars`.`genesis_id` = ? AND `avatars`.`floor_id` = ?',
            [
                ['type' => 'i', 'value' => $id],
                ['type' => 'i', 'value' => $genesisId],
                ['type' => 'i', 'value' => $floorId],
            ],
            true
        );

        if (!$data) {
            return null;
        }

        return AvatarFactory::create($data);
    }
}
