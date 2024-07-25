<?php

declare(strict_types=1);

namespace App\Domain\Account\Character;

use App\Domain\Account\AccountException;
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
     * @throws AccountException
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
            `avatars`.`origin_rul` as `avatar`,
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
}
