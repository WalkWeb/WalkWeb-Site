<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Profession;

use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;

class ProfessionRepository
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param int $id
     * @param int $genesisId
     * @return ProfessionInterface
     * @throws AppException
     */
    public function get(int $id, int $genesisId): ?ProfessionInterface
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT 

            `professions`.`id` as `profession_id`,
            `professions`.`icon` as `profession_icon`,
            `professions`.`name_male` as `profession_name_male`,
            `professions`.`name_female` as `profession_name_female`,
            `genesis`.`id` as `genesis_id`,
            `genesis`.`theme_id` as `theme_id`,
            `genesis`.`icon` as `genesis_icon`,
            `genesis`.`plural` as `genesis_plural`,
            `genesis`.`single` as `genesis_single`
       
            FROM `professions`

            JOIN `genesis` ON `professions`.`genesis_id` = `genesis`.`id`

            WHERE `professions`.`id` = ? AND `professions`.`genesis_id` = ?',
            [
                ['type' => 'i', 'value' => $id],
                ['type' => 'i', 'value' => $genesisId],
            ],
            true
        );

        if (!$data) {
            return null;
        }

        return ProfessionFactory::create($data);
    }
}
