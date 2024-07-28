<?php

declare(strict_types=1);

namespace App\Domain\Account\Character;

use App\Domain\Account\MainCharacter\MainCharacterInterface;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;

class CharacterRepository
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $id
     * @return CharacterInterface|null
     * @throws AppException
     */
    public function get(string $id): ?CharacterInterface
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
        'SELECT 
   
            `characters`.`id` as `character_id`,
            `characters`.`level` as `character_level`,
            `characters`.`exp` as `character_exp`,
            `characters`.`stats_point` as `character_stat_points`,
            `characters`.`skill_point` as `character_skill_points`,
            `characters_main`.`account_id`,
            `accounts`.`name` as `account_name`,
            `characters`.`character_main_id` as `main_character_id`,
            `avatars`.`id` as `avatar_id`,
            `avatars`.`origin_url` as `avatar`,
            `characters`.`season_id`,
            `characters`.`floor_id`,
            `characters`.`genesis_id`,
            `genesis`.`theme_id`,
            `genesis`.`icon` as `genesis_icon`,
            `genesis`.`plural` as `genesis_plural`,
            `genesis`.`single` as `genesis_single`,
            `professions`.`id` as `profession_id`,
            `professions`.`icon` as `profession_icon`,
            `professions`.`name_male` as `profession_name_male`,
            `professions`.`name_female` as `profession_name_female`
            
            FROM `characters` 

            JOIN `accounts` ON `characters`.`id` = `accounts`.`character_id`
            JOIN `characters_main` ON `characters`.`character_main_id` = `characters_main`.`id`
            JOIN `avatars` ON `characters`.`avatar_id` = `avatars`.`id`
            JOIN `professions` ON `characters`.`profession_id` = `professions`.`id`
            JOIN `genesis` ON `avatars`.`genesis_id` = `genesis`.`id`

            WHERE `characters`.`id` = ?',
            [['type' => 's', 'value' => $id]],
            true
        );

        if (!$data) {
            return null;
        }

        return CharacterFactory::create($data);
    }

    /**
     * @param CharacterInterface $character
     * @param MainCharacterInterface $mainCharacter
     * @throws AppException
     */
    public function add(
        CharacterInterface $character, MainCharacterInterface $mainCharacter): void
    {
        $this->container->getConnectionPool()->getConnection()->query(
            'INSERT INTO `characters` (
                    `id`, `character_main_id`, `season_id`, `genesis_id`, `profession_id`, `avatar_id`, `floor_id`,
                    `level`, `exp`, `stats_point`, `skill_point`
                    
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                ['type' => 's', 'value' => $character->getId()],
                ['type' => 's', 'value' => $mainCharacter->getId()],
                ['type' => 'i', 'value' => $character->getSeason()->getId()],
                ['type' => 'i', 'value' => $character->getGenesis()->getId()],
                ['type' => 'i', 'value' => $character->getProfession()->getId()],
                ['type' => 'i', 'value' => $character->getAvatarId()],
                ['type' => 'i', 'value' => $character->getFloor()->getId()],
                ['type' => 'i', 'value' => $character->getLevel()->getLevel()],
                ['type' => 'i', 'value' => $character->getLevel()->getExp()],
                ['type' => 'i', 'value' => $character->getLevel()->getStatPoints()],
                ['type' => 'i', 'value' => $character->getLevel()->getSkillPoints()],
            ]
        );
    }
}
