<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Collection;

use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;

class CharacterCollectionRepository
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $mainCharacterId
     * @return CharacterCollection
     * @throws AppException
     */
    public function get(string $mainCharacterId): CharacterCollection
    {
        return CharacterCollectionFactory::create($this->container->getConnectionPool()->getConnection()->query(
        'SELECT 
            
            `characters`.`id`, 
            `characters`.`level`, 
            `characters`.`floor_id`,
            `genesis`.`single` as `genesis`,
            `professions`.`name_male` as `profession_name_male`,
            `professions`.`name_female` as `profession_name_female`,
            `avatars`.`origin_url` as `avatar`
            
            FROM `characters` 
            
            JOIN `genesis` ON `characters`.`genesis_id` = `genesis`.`id`
            JOIN `professions` ON `characters`.`profession_id` = `professions`.`id`
            JOIN `avatars` ON `characters`.`avatar_id` = `avatars`.`id`
    
            WHERE `character_main_id` = ?',
            [['type' => 's', 'value' => $mainCharacterId]],
        ));
    }
}
