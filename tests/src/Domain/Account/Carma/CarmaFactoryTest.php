<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Carma;

use App\Domain\Account\Carma\CarmaException;
use App\Domain\Account\Carma\CarmaFactory;
use Ramsey\Uuid\Uuid;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class CarmaFactoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $data
     * @throws AppException
     */
    public function testCarmaFactoryCreateSuccess(array $data): void
    {
        $carma = CarmaFactory::create($data);

        self::assertEquals($data['id'], $carma->getId());
        self::assertEquals($data['account_id'], $carma->getAccountId());
        self::assertEquals($data['season_id'], $carma->getSeason()->getId());
        self::assertEquals($data['carma'], $carma->getCarma());
        self::assertEquals($data['uses'], $carma->getUses());
        self::assertEquals($data['carma'] - $data['uses'], $carma->getAvailable());
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     * @throws AppException
     */
    public function testCarmaFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        CarmaFactory::create($data);
    }

    /**
     * @throws AppException
     */
    public function testCarmaFactoryCreateNewSuccess(): void
    {
        $accountId = 'd69c730c-328e-4c2c-a73f-a6461c2831a5';
        $carma = CarmaFactory::createNew($accountId);

        self::assertTrue(Uuid::isValid($carma->getId()));
        self::assertEquals($accountId, $carma->getAccountId());
        self::assertEquals(ACTIVE_SEASON, $carma->getSeason()->getId());
        self::assertEquals(0, $carma->getCarma());
        self::assertEquals(0, $carma->getUses());
        self::assertEquals(0, $carma->getAvailable());
    }

    public function testCarmaFactoryCreateNewFail(): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage(CarmaException::INVALID_NEW);
        CarmaFactory::createNew('invalid_uuid');
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    'id'         => 'cee36175-7f1b-432a-b5cb-cc67432fb5b3',
                    'account_id' => '76c09c1e-93db-47a0-bf3b-9a9795c817b1',
                    'season_id'  => 1,
                    'carma'      => 0,
                    'uses'       => 0,
                ],
            ],
            [
                [
                    'id'         => 'cee36175-7f1b-432a-b5cb-cc67432fb5b3',
                    'account_id' => '76c09c1e-93db-47a0-bf3b-9a9795c817b1',
                    'season_id'  => 1,
                    'carma'      => 20,
                    'uses'       => 10,
                ],
            ],
            [
                [
                    'id'         => 'cee36175-7f1b-432a-b5cb-cc67432fb5b3',
                    'account_id' => '76c09c1e-93db-47a0-bf3b-9a9795c817b1',
                    'season_id'  => 1,
                    'carma'      => -30,
                    'uses'       => -10,
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function failDataProvider(): array
    {
        return [
            // miss id
            [
                [
                    'account_id' => '76c09c1e-93db-47a0-bf3b-9a9795c817b1',
                    'season_id'  => 1,
                    'carma'      => 0,
                    'uses'       => 0,
                ],
                CarmaException::INVALID_ID,
            ],
            // id invalid type
            [
                [
                    'id'         => 100,
                    'account_id' => '76c09c1e-93db-47a0-bf3b-9a9795c817b1',
                    'season_id'  => 1,
                    'carma'      => 0,
                    'uses'       => 0,
                ],
                CarmaException::INVALID_ID,
            ],
            // id invalid uuid
            [
                [
                    'id'         => '10ae6563-8caf-4107-84ad-6dafb6526xxx',
                    'account_id' => '76c09c1e-93db-47a0-bf3b-9a9795c817b1',
                    'season_id'  => 1,
                    'carma'      => 0,
                    'uses'       => 0,
                ],
                CarmaException::INVALID_ID,
            ],
            // miss account_id
            [
                [
                    'id'         => 'cee36175-7f1b-432a-b5cb-cc67432fb5b3',
                    'season_id'  => 1,
                    'carma'      => 0,
                    'uses'       => 0,
                ],
                CarmaException::INVALID_ACCOUNT_ID,
            ],
            // account_id invalid type
            [
                [
                    'id'         => 'cee36175-7f1b-432a-b5cb-cc67432fb5b3',
                    'account_id' => 123,
                    'season_id'  => 1,
                    'carma'      => 0,
                    'uses'       => 0,
                ],
                CarmaException::INVALID_ACCOUNT_ID,
            ],
            // account_id invalid type
            [
                [
                    'id'         => 'cee36175-7f1b-432a-b5cb-cc67432fb5b3',
                    'account_id' => '559f534e-7f44-488a-9984-aa4fea2686e3___',
                    'season_id'  => 1,
                    'carma'      => 0,
                    'uses'       => 0,
                ],
                CarmaException::INVALID_ACCOUNT_ID,
            ],
            // miss season_id
            [
                [
                    'id'         => 'cee36175-7f1b-432a-b5cb-cc67432fb5b3',
                    'account_id' => '76c09c1e-93db-47a0-bf3b-9a9795c817b1',
                    'carma'      => 0,
                    'uses'       => 0,
                ],
                CarmaException::INVALID_SEASON_ID,
            ],
            // season_id invalid type
            [
                [
                    'id'         => 'cee36175-7f1b-432a-b5cb-cc67432fb5b3',
                    'account_id' => '76c09c1e-93db-47a0-bf3b-9a9795c817b1',
                    'season_id'  => '1',
                    'carma'      => 0,
                    'uses'       => 0,
                ],
                CarmaException::INVALID_SEASON_ID,
            ],
            // miss carma
            [
                [
                    'id'         => 'cee36175-7f1b-432a-b5cb-cc67432fb5b3',
                    'account_id' => '76c09c1e-93db-47a0-bf3b-9a9795c817b1',
                    'season_id'  => 1,
                    'uses'       => 0,
                ],
                CarmaException::INVALID_CARMA,
            ],
            // carma invalid type
            [
                [
                    'id'         => 'cee36175-7f1b-432a-b5cb-cc67432fb5b3',
                    'account_id' => '76c09c1e-93db-47a0-bf3b-9a9795c817b1',
                    'season_id'  => 1,
                    'carma'      => null,
                    'uses'       => 0,
                ],
                CarmaException::INVALID_CARMA,
            ],
            // miss uses
            [
                [
                    'id'         => 'cee36175-7f1b-432a-b5cb-cc67432fb5b3',
                    'account_id' => '76c09c1e-93db-47a0-bf3b-9a9795c817b1',
                    'season_id'  => 1,
                    'carma'      => 0,
                ],
                CarmaException::INVALID_USES,
            ],
            // uses invalid type
            [
                [
                    'id'         => 'cee36175-7f1b-432a-b5cb-cc67432fb5b3',
                    'account_id' => '76c09c1e-93db-47a0-bf3b-9a9795c817b1',
                    'season_id'  => 1,
                    'carma'      => 0,
                    'uses'       => true,
                ],
                CarmaException::INVALID_USES,
            ],
        ];
    }
}
