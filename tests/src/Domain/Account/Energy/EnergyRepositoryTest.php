<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Energy;

use App\Domain\Account\Energy\EnergyException;
use App\Domain\Account\Energy\EnergyFactory;
use App\Domain\Account\Energy\EnergyInterface;
use App\Domain\Account\Energy\EnergyRepository;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class EnergyRepositoryTest extends AbstractTest
{
    /**
     * @throws AppException
     */
    public function testEnergyRepositoryAddSuccess(): void
    {
        $energy = EnergyFactory::createNew();
        $repository = new EnergyRepository(self::getContainer());
        $repository->add($energy);

        $energyDb = $repository->get($energy->getId());

        self::assertEquals($energy->getId(), $energyDb->getId());
        self::assertEquals($energy->getEnergy(), $energyDb->getEnergy());
        self::assertEquals($energy->getMaxEnergy(), $energyDb->getMaxEnergy());
        self::assertEquals($energy->getUpdatedAt(), $energyDb->getUpdatedAt());
        self::assertEquals($energy->getResidue(), $energyDb->getResidue());
    }

    /**
     * @throws AppException
     */
    public function testEnergyRepositoryGetSuccess(): void
    {
        $id = '2dad01e1-af9d-479d-9f48-92823f585801';
        $repository = new EnergyRepository(self::getContainer());

        $energy = $repository->get($id);

        self::assertEquals($id, $energy->getId());
        self::assertEquals(EnergyInterface::BASE_ENERGY, $energy->getEnergy());
        self::assertEquals(EnergyInterface::BASE_ENERGY, $energy->getMaxEnergy());
        self::assertEquals(1583780978.0000, $energy->getUpdatedAt());
        self::assertEquals($energy->getResidue(), $energy->getResidue());
        self::assertEquals(round((float)microtime(true), 1), round($energy->getTime(), 1));
    }

    /**
     * @throws AppException
     */
    public function testEnergyRepositoryGetFail(): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage(EnergyException::NOT_FOUND);
        $repository = new EnergyRepository(self::getContainer());
        $repository->get('e9334d4c-20d5-4650-9917-63b9ae4f1791');
    }
}
