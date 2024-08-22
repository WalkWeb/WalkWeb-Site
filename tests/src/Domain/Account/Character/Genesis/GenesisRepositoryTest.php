<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Character\Genesis;

use App\Domain\Account\Character\Genesis\GenesisRepository;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class GenesisRepositoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param int $id
     * @param int $themeId
     * @param string $icon
     * @param string $plural
     * @param string $single
     * @throws AppException
     */
    public function testGenesisRepositoryGetSuccess(int $id, int $themeId, string $icon, string $plural, string $single): void
    {
        $genesis = $this->getRepository()->get($id, $themeId);

        self::assertEquals($id, $genesis->getId());
        self::assertEquals($themeId, $genesis->getTheme()->getId());
        self::assertEquals($icon, $genesis->getIcon());
        self::assertEquals($plural, $genesis->getPlural());
        self::assertEquals($single, $genesis->getSingle());
    }

    /**
     * @throws AppException
     */
    public function testGenesisRepositoryGetNotFound(): void
    {
        self::assertNull($this->getRepository()->get(123, 1));
    }

    /**
     * @dataProvider topDataProvider
     * @param int $themeId
     * @param array $expectedTop
     * @throws AppException
     */
    public function testGenesisRepositoryGetTop(int $themeId, array $expectedTop): void
    {
        $top = $this->getRepository()->getTop($themeId);

        self::assertCount(6, $top);

        $i = 0;
        foreach ($top as $genesis) {
            self::assertEquals($expectedTop[$i], $genesis->getName());
            $i++;
        }
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                1,
                1,
                '/img/icon/genesis_default.png',
                'Analysts',
                'Analyst',
            ],
            [
                6,
                1,
                '/img/icon/genesis_default.png',
                'Managers',
                'Manager',
            ],
        ];
    }

    /**
     * @return array
     */
    public function topDataProvider(): array
    {
        return [
            [
                1,
                [
                    'Designers',
                    'Devops',
                    'Analysts',
                    'Trainees',
                    'Programmers',
                    'Managers',
                ],
            ],
            [
                2,
                [
                    'Angels',
                    'People',
                    'Elves',
                    'Orcs',
                    'Dwarfs',
                    'Demons',
                ],
            ],
        ];
    }

    /**
     * @return GenesisRepository
     * @throws AppException
     */
    private function getRepository(): GenesisRepository
    {
        return new GenesisRepository(self::getContainer());
    }
}
