<?php

declare(strict_types=1);

namespace App\Domain\Account\MainCharacter;

use App\Domain\Account\Notice\Action\SendNoticeActionInterface;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Response;

class MainCharacterRepository
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    // TODO add

    /**
     * @param string $id
     * @param SendNoticeActionInterface $sendNoticeAction
     * @return MainCharacterInterface
     * @throws AppException
     */
    public function get(string $id, SendNoticeActionInterface $sendNoticeAction): MainCharacterInterface
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT 
       
            `id` as `character_id`, 
            `account_id`, 
            `era_id`,
            `level` as `character_level`, 
            `exp` as `character_exp`, 
            `energy_bonus`, 
            `upload_bonus`, 
            `stats_point` as `character_stat_points`

            FROM `characters_main` WHERE `id` = ?',
            [['type' => 's', 'value' => $id]],
            true
        );

        if (!$data) {
            throw new AppException(MainCharacterException::NOT_FOUND, Response::NOT_FOUND);
        }

        return MainCharacterFactory::create($data, $sendNoticeAction);
    }

    /**
     * @param MainCharacterInterface $character
     * @throws AppException
     */
    public function update(MainCharacterInterface $character): void
    {
        $this->container->getConnectionPool()->getConnection()->query(
            'UPDATE `characters_main` SET `level` = ?, `exp` = ?, `stats_point` = ? WHERE `id` = ?',
            [
                ['type' => 'i', 'value' => $character->getLevel()->getLevel()],
                ['type' => 'i', 'value' => $character->getLevel()->getExp()],
                ['type' => 'i', 'value' => $character->getLevel()->getStatPoints()],
                ['type' => 's', 'value' => $character->getId()],
            ]
        );
    }
}
