<?php

declare(strict_types=1);

namespace Tests\src\Domain\Account\Energy;

use Exception;
use App\Domain\Account\Energy\Energy;
use App\Domain\Account\Energy\EnergyException;
use App\Domain\Account\Energy\EnergyInterface;
use Tests\AbstractTest;

class EnergyTest extends AbstractTest
{
    /**
     * Тест на создание Energy в котором последнее обновление энергии было давно, и она становится максимальной, не
     * смотря на то, что передается не максимальная энергия в конструктор
     */
    public function testEnergyCreateUpdatedToFull(): void
    {
        $id = '23474820-3e4f-45e3-ba0b-78d202f56ad5';
        $actualEnergy = 50;
        $maxEnergy = 100;
        $time = (float)microtime(true);
        $updatedAt = 1566745426.0000;
        $residue = 20;

        $energy = new Energy($id, $actualEnergy, $maxEnergy, $time, $updatedAt, $residue);

        self::assertEquals($id, $energy->getId());
        self::assertEquals($maxEnergy, $energy->getMaxEnergy());
        self::assertEquals($time, $energy->getTime());
        self::assertEquals($updatedAt, $energy->getUpdatedAt());
        self::assertFalse($energy->isUpdated());

        self::assertEquals(100, $energy->getEnergyWeight());
        self::assertEquals(0, $energy->getRestoreWeight());

        // Энергия максимальная, т.к. прошло много времени с её последнего обновления и она вся восстановилась
        self::assertEquals($maxEnergy, $energy->getEnergy());

        // Остаток секунд должен сброситься, т.к. энергия стала максимальной
        self::assertEquals(0, $energy->getResidue());
    }

    /**
     * Тест на создание Energy в котором последнее обновление энергии было только что
     * Соответственно энергия и остаток секунд не должен меняться
     */
    public function testEnergyCreateNow(): void
    {
        // Делается несколько проверок с различным сдвигом по времени, чтобы проверить разные варианты округления
        for ($i = 0; $i < 10; $i++) {
            $id = '23474820-3e4f-45e3-ba0b-78d202f56ad5';
            $actualEnergy = 50;
            $maxEnergy = 100;
            $time = (float)microtime(true) + $i / 10;
            $updatedAt = (float)microtime(true) + $i / 10;
            $residue = 10;

            $energy = new Energy($id, $actualEnergy, $maxEnergy, $time, $updatedAt, $residue);

            self::assertEquals($id, $energy->getId());
            self::assertEquals($maxEnergy, $energy->getMaxEnergy());
            self::assertEquals($time, $energy->getTime());
            self::assertEquals($updatedAt, $energy->getUpdatedAt());
            self::assertFalse($energy->isUpdated());

            self::assertEquals($actualEnergy, $energy->getEnergy());
            self::assertEquals($residue, $energy->getResidue());
        }
    }

    /**
     * Тест на создание Energy когда передаются некорректные данные - энергия максимально, при этом есть остаток секунд
     * (хотя его в такой ситуации не должно быть)
     */
    public function testEnergyCreateUpdateResidue(): void
    {
        $id = '23474820-3e4f-45e3-ba0b-78d202f56ad5';
        $actualEnergy = 100;
        $maxEnergy = 100;
        $time = (float)microtime(true);
        $updatedAt = (float)microtime(true);
        $residue = 10;

        $energy = new Energy($id, $actualEnergy, $maxEnergy, $time, $updatedAt, $residue);

        self::assertEquals(0, $energy->getResidue());
    }

    /**
     * Тест на корректировку энергии, когда она больше максимальной - просто уменьшаем до максимальной.
     *
     * Это сделано на случай, если бонус к энергии вдруг уменьшился, чтобы все продолжило нормально работать - энергия
     * не стала больше максимальной, но чтобы и ошибок не было
     */
    public function testEnergyCreateCorrectOverMaxEnergy(): void
    {
        $id = '23474820-3e4f-45e3-ba0b-78d202f56ad5';
        $actualEnergy = 200;
        $maxEnergy = 100;
        $time = (float)microtime(true);
        $updatedAt = (float)microtime(true);
        $residue = 10;

        $energy = new Energy($id, $actualEnergy, $maxEnergy, $time, $updatedAt, $residue);

        self::assertEquals($maxEnergy, $energy->getEnergy());
    }

    /**
     * Тест на корректировку residue, когда он оказался больше чем EnergyInterface::RESTORE (например значение уменьшили)
     *
     * В этом случае residue просто уменьшается до значения EnergyInterface::RESTORE
     *
     * При этом сразу происходит перерасчет энергии, и остаток секунд сбрасывается до 0, а энергия увеличивается на 1
     */
    public function testEnergyCreateCorrectOverMaxResidue(): void
    {
        $id = '23474820-3e4f-45e3-ba0b-78d202f56ad5';
        $actualEnergy = 50;
        $maxEnergy = 100;
        $time = (float)microtime(true);
        $updatedAt = (float)microtime(true);
        $residue = 61;

        $energy = new Energy($id, $actualEnergy, $maxEnergy, $time, $updatedAt, $residue);

        self::assertEquals(0, $energy->getResidue());
        self::assertEquals($actualEnergy + 1, $energy->getEnergy());
    }

    /**
     * Тест на корректировку residue, когда он меньше 0 - он приравнивается к 0, exception не кидается
     */
    public function testEnergyCreateCorrectOverMinResidue(): void
    {
        $id = '23474820-3e4f-45e3-ba0b-78d202f56ad5';
        $actualEnergy = 50;
        $maxEnergy = 100;
        $time = (float)microtime(true);
        $updatedAt = (float)microtime(true);
        $residue = -100;

        $energy = new Energy($id, $actualEnergy, $maxEnergy, $time, $updatedAt, $residue);

        self::assertEquals(0, $energy->getResidue());
        self::assertEquals($actualEnergy, $energy->getEnergy());
    }

    /**
     * Тест на добавление энергии, когда она не становится максимальной
     *
     * @throws EnergyException
     */
    public function testEnergyEditDefault(): void
    {
        $id = '23474820-3e4f-45e3-ba0b-78d202f56ad5';
        $actualEnergy = 50;
        $addEnergy = 25;
        $maxEnergy = 80;
        $time = (float)microtime(true);
        $updatedAt = (float)microtime(true);
        $residue = 15;

        $energy = new Energy($id, $actualEnergy, $maxEnergy, $time, $updatedAt, $residue);

        $message = $energy->editEnergy($addEnergy);

        self::assertEquals($actualEnergy + $addEnergy, $energy->getEnergy());
        self::assertEquals($residue, $energy->getResidue());
        self::assertTrue($energy->isUpdated());

        self::assertEquals(EnergyInterface::SUCCESS_ADDED, $message);
    }

    /**
     * Тест на добавление энергии, когда она становится максимальной и остаток сбрасывается
     *
     * @throws EnergyException
     */
    public function testEnergyEditToMax(): void
    {
        $id = '23474820-3e4f-45e3-ba0b-78d202f56ad5';
        $actualEnergy = 50;
        $addEnergy = 75;
        $maxEnergy = 100;
        $time = (float)microtime(true);
        $updatedAt = (float)microtime(true);
        $residue = 10;

        $energy = new Energy($id, $actualEnergy, $maxEnergy, $time, $updatedAt, $residue);

        $message = $energy->editEnergy($addEnergy);

        self::assertEquals($maxEnergy, $energy->getEnergy());
        self::assertEquals(0, $energy->getResidue());
        self::assertTrue($energy->isUpdated());

        self::assertEquals(EnergyInterface::SUCCESS_ADDED, $message);
    }

    /**
     * Тест на добавление энергии, когда она уже максимальная
     */
    public function testEnergyAddAlreadyMax(): void
    {
        $id = '23474820-3e4f-45e3-ba0b-78d202f56ad5';
        $actualEnergy = 100;
        $addEnergy = 75;
        $maxEnergy = 100;
        $time = (float)microtime(true);
        $updatedAt = (float)microtime(true);
        $residue = 0;

        $energy = new Energy($id, $actualEnergy, $maxEnergy, $time, $updatedAt, $residue);

        try {
            $energy->editEnergy($addEnergy);
        } catch (Exception $e) {
            self::assertEquals(EnergyException::ALREADY_MAX, $e->getMessage());
        }

        self::assertEquals($maxEnergy, $energy->getEnergy());
        self::assertEquals(0, $energy->getResidue());
        self::assertFalse($energy->isUpdated());
    }

    /**
     * Тест на уменьшение энергии, когда её не хватает
     */
    public function testEnergyNoEnough(): void
    {
        $id = '23474820-3e4f-45e3-ba0b-78d202f56ad5';
        $actualEnergy = 10;
        $addEnergy = -30;
        $maxEnergy = 100;
        $time = (float)microtime(true);
        $updatedAt = (float)microtime(true);
        $residue = 10;

        $energy = new Energy($id, $actualEnergy, $maxEnergy, $time, $updatedAt, $residue);

        try {
            $energy->editEnergy($addEnergy);
        } catch (Exception $e) {
            self::assertEquals(sprintf(EnergyException::NO_ENOUGH, $actualEnergy, abs($addEnergy)), $e->getMessage());
        }

        self::assertEquals($actualEnergy, $energy->getEnergy());
        self::assertEquals($residue, $energy->getResidue());
        self::assertFalse($energy->isUpdated());
    }

    /**
     * Тест на уменьшение энергии, когда её не хватает
     *
     * @throws EnergyException
     */
    public function testEnergySuccessReduced(): void
    {
        $id = '23474820-3e4f-45e3-ba0b-78d202f56ad5';
        $actualEnergy = 50;
        $addEnergy = -30;
        $maxEnergy = 100;
        $time = (float)microtime(true);
        $updatedAt = (float)microtime(true);
        $residue = 10;

        $energy = new Energy($id, $actualEnergy, $maxEnergy, $time, $updatedAt, $residue);

        $message = $energy->editEnergy($addEnergy);

        self::assertEquals($actualEnergy + $addEnergy, $energy->getEnergy());
        self::assertEquals($residue, $energy->getResidue());
        self::assertTrue($energy->isUpdated());

        self::assertEquals(EnergyInterface::SUCCESS_REDUCED, $message);
    }

    /**
     * Тест на ситуацию, когда передано 0 значение на изменение энергии
     */
    public function testEnergyEditZeroValue(): void
    {
        $id = '23474820-3e4f-45e3-ba0b-78d202f56ad5';
        $actualEnergy = 10;
        $addEnergy = 0;
        $maxEnergy = 100;
        $time = (float)microtime(true);
        $updatedAt = (float)microtime(true);
        $residue = 10;

        $energy = new Energy($id, $actualEnergy, $maxEnergy, $time, $updatedAt, $residue);

        try {
            $energy->editEnergy($addEnergy);
        } catch (Exception $e) {
            self::assertEquals(EnergyException::ZERO_VALUE, $e->getMessage());
        }

        self::assertEquals($actualEnergy, $energy->getEnergy());
        self::assertEquals($residue, $energy->getResidue());
        self::assertFalse($energy->isUpdated());
    }
}
