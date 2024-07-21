<?php

declare(strict_types=1);

namespace App\Domain\Account\Energy;

use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;

class EnergyRepository
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $id
     * @return EnergyInterface
     * @throws AppException
     */
    public function get(string $id): EnergyInterface
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT 
       
           `id` as `energy_id`, 
           `energy`, 
           `updated_at` as `energy_updated_at`, 
           `residue` as `energy_residue`, 
           `bonus` as `energy_bonus` 

            FROM `account_energy` WHERE `id` = ?',
            [['type' => 's', 'value' => $id]],
            true
        );

        if (!$data) {
            throw new AppException(EnergyException::NOT_FOUND);
        }

        $data['energy_updated_at'] = (float)$data['energy_updated_at'];

        return EnergyFactory::create($data);
    }

    /**
     * @param EnergyInterface $energy
     * @throws AppException
     */
    public function add(EnergyInterface $energy): void
    {
        $this->container->getConnectionPool()->getConnection()->query(
            'INSERT INTO `account_energy` (`id`, `energy`, `updated_at`, `residue`) VALUES (?, ?, ?, ?)',
            [
                ['type' => 's', 'value' => $energy->getId()],
                ['type' => 'i', 'value' => $energy->getEnergy()],
                ['type' => 's', 'value' => (string)$energy->getUpdatedAt()],
                ['type' => 'i', 'value' => $energy->getResidue()],
            ],
        );
    }

    /**
     * @param EnergyInterface $energy
     * @throws AppException
     */
    public function save(EnergyInterface $energy): void
    {
        $this->container->getConnectionPool()->getConnection()->query(
            'UPDATE `account_energy` SET `energy` = ?, `updated_at` = ?, `residue` = ? WHERE `id` = ?',
            [
                ['type' => 'i', 'value' => $energy->getEnergy()],
                ['type' => 's', 'value' => $energy->getUpdatedAt()],
                ['type' => 'i', 'value' => $energy->getResidue()],
                ['type' => 's', 'value' => $energy->getId()],
            ],
        );
    }
}
