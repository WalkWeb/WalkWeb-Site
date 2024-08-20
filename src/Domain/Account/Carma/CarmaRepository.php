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
     * @param string $accountId
     * @param int $seasonId
     * @return CarmaInterface|null
     * @throws AppException
     */
    public function get(string $accountId, int $seasonId): ?CarmaInterface
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT
       
                `id` as `carma_id`,
                `account_id` as `id`,
                `season_id`,
                `carma`,
                `uses` as `carma_uses`

                FROM `account_carma` WHERE `account_id` = ? AND `season_id` = ?',
            [
                ['type' => 's', 'value' => $accountId],
                ['type' => 'i', 'value' => $seasonId],
            ],
            true
        );

        if (!$data) {
            return null;
        }

        return CarmaFactory::create($data);
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
