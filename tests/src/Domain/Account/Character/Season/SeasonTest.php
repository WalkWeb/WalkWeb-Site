<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Character\Season;

use App\Domain\Account\Character\CharacterException;
use App\Domain\Account\Character\Season\Season;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class SeasonTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param int $id
     * @param string $expectedName
     * @throws AppException
     */
    public function testSeasonCreateSuccess(int $id, string $expectedName): void
    {
        $season = new Season($id);

        self::assertEquals($id, $season->getId());
        self::assertEquals($expectedName, $season->getName());
    }

    /**
     * @dataProvider failDataProvider
     * @param int $id
     * @param string $error
     * @throws AppException
     */
    public function testSeasonCreateFail(int $id, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        new Season($id);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                1,
                'Season-1',
            ],
        ];
    }

    /**
     * @return array
     */
    public function failDataProvider(): array
    {
        return [
            [
                4,
                CharacterException::UNKNOWN_SEASON_ID . ': 4',
            ],
        ];
    }
}
