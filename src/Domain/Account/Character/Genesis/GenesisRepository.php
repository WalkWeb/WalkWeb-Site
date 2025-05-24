<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Genesis;

use App\Domain\Rating\DTO\Genesis\GenesisRatingCollection;
use App\Domain\Rating\DTO\Genesis\GenesisRatingCollectionFactory;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;

class GenesisRepository
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param int $id
     * @param int $themeId
     * @return GenesisInterface|null
     * @throws AppException
     */
    public function get(int $id, int $themeId): ?GenesisInterface
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT 
       
            `id` as `genesis_id`,  
            `theme_id`,
            `icon` as `genesis_icon`,  
            `plural` as `genesis_plural`,  
            `single` as `genesis_single`

            FROM `genesis` WHERE `id` = ? AND `theme_id` = ?',
            [
                ['type' => 'i', 'value' => $id],
                ['type' => 'i', 'value' => $themeId],
            ],
            true
        );

        if (!$data) {
            return null;
        }

        return GenesisFactory::create($data);
    }

    /**
     * TODO Подумать над переделкой рейтинга - чтобы брался не активный персонаж у аккаунта, а самый высокоуровневый
     *
     * @param int $themeId
     * @return GenesisRatingCollection
     * @throws AppException
     */
    public function getTop(int $themeId): GenesisRatingCollection
    {
        return GenesisRatingCollectionFactory::create($this->container->getConnectionPool()->getConnection()->query(
            'SELECT

                `genesis`.`id`,
                `genesis`.`icon`,
                `genesis`.`plural` as `name`,
                COUNT(`accounts`.`id`) as `member_count`,
                SUM(`accounts`.`post_count`) as `post_count`,
                SUM(`accounts`.`comment_count`) as `comment_count`,
                SUM(`account_carma`.`carma`) as `carma_count`
                
                FROM `genesis`
                
                LEFT JOIN `characters` ON `genesis`.`id` = `characters`.`genesis_id`
                LEFT JOIN `accounts` ON `characters`.`id` = `accounts`.`character_id`
                LEFT JOIN `account_carma` ON `accounts`.`id` = `account_carma`.`account_id`
                
                WHERE `genesis`.`theme_id` = ? and `genesis`.`playlable` = 1
                
                GROUP BY `genesis`.`id`, `genesis`.`icon`, `genesis`.`plural`
                
                ORDER BY `carma_count` DESC, `genesis`.`id`',
            [['type' => 'i', 'value' => $themeId]],
        ));
    }
}
