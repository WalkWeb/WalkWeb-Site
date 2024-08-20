<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Carma;

use App\Domain\Account\Carma\CarmaRepository;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class CarmaRepositoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param string $accountId
     * @param int $seasonId
     * @throws AppException
     */
    public function testCarmaRepositoryGetSuccess(string $accountId, int $seasonId): void
    {
        $carma = $this->getRepository()->get($accountId, $seasonId);
        $data = $this->getData($accountId, $seasonId);

        self::assertEquals($data['carma_id'], $carma->getId());
        self::assertEquals($data['id'], $carma->getAccountId());
        self::assertEquals($data['season_id'], $carma->getSeason()->getId());
        self::assertEquals($data['carma'], $carma->getCarma());
        self::assertEquals($data['carma_uses'], $carma->getUses());
    }

    /**
     * @throws AppException
     */
    public function testCarmaRepositoryGetNotFound(): void
    {
        self::assertNull($this->getRepository()->get(
            '6c19c340-9da7-4130-833a-7cba43af752d',
            ACTIVE_SEASON)
        );
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                self::DEMO_USER,
                1,
            ],
        ];
    }

    /**
     * @return CarmaRepository
     * @throws AppException
     */
    private function getRepository(): CarmaRepository
    {
        return new CarmaRepository(self::getContainer());
    }

    /**
     * @param string $accountId
     * @param int $seasonId
     * @return array
     * @throws AppException
     */
    private function getData(string $accountId, int $seasonId): array
    {
        return self::getContainer()->getConnectionPool()->getConnection()->query(
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
    }
}
