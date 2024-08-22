<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Genesis;

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
}
