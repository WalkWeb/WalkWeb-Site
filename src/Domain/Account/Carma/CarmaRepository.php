<?php

declare(strict_types=1);

namespace App\Domain\Account\Carma;

use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;

class CarmaRepository
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param CarmaInterface $carma
     * @throws AppException
     */
    public function add(CarmaInterface $carma): void
    {
        $this->container->getConnectionPool()->getConnection()->query(
            'INSERT INTO `account_carma` (`id`, `account_id`, `season_id`, `carma`, `uses`) VALUES (?, ?, ?, ?, ?)',
            [
                ['type' => 's', 'value' => $carma->getId()],
                ['type' => 's', 'value' => $carma->getAccountId()],
                ['type' => 'i', 'value' => $carma->getSeason()->getId()],
                ['type' => 'i', 'value' => $carma->getCarma()],
                ['type' => 'i', 'value' => $carma->getUses()],
            ]
        );
    }
}
