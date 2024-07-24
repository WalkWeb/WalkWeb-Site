<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Character\Genesis;

use App\Domain\Account\Character\Genesis\GenesisException;
use App\Domain\Account\Character\Genesis\GenesisFactory;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class GenesisFactoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $data
     * @throws AppException
     */
    public function testGenesisFactoryCreateSuccess(array $data): void
    {
        $genesis = GenesisFactory::create($data);

        self::assertEquals($data['genesis_id'], $genesis->getId());
        self::assertEquals($data['theme_id'], $genesis->getTheme()->getId());
        self::assertEquals($data['genesis_icon'], $genesis->getIcon());
        self::assertEquals($data['genesis_plural'], $genesis->getPlural());
        self::assertEquals($data['genesis_single'], $genesis->getSingle());
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     * @throws AppException
     */
    public function testGenesisFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        GenesisFactory::create($data);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    'genesis_id'     => 1,
                    'theme_id'       => 1,
                    'genesis_icon'   => 'icon-1.png',
                    'genesis_plural' => 'Analysts',
                    'genesis_single' => 'Analyst',
                ],
            ],
            [
                [
                    'genesis_id'     => 4,
                    'theme_id'       => 1,
                    'genesis_icon'   => 'icon-2.png',
                    'genesis_plural' => 'Trainees',
                    'genesis_single' => 'Intern',
                ],
            ],
            [
                [
                    'genesis_id'     => 6,
                    'theme_id'       => 1,
                    'genesis_icon'   => 'icon-3.png',
                    'genesis_plural' => 'Managers',
                    'genesis_single' => 'Manager',
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
                    'theme_id'       => 1,
                    'genesis_icon'   => 'icon-1.png',
                    'genesis_plural' => 'Analysts',
                    'genesis_single' => 'Analyst',
                ],
                GenesisException::INVALID_ID,
            ],
            // id invalid type
            [
                [
                    'genesis_id'     => '4',
                    'theme_id'       => 1,
                    'genesis_icon'   => 'icon-2.png',
                    'genesis_plural' => 'Trainees',
                    'genesis_single' => 'Intern',
                ],
                GenesisException::INVALID_ID,
            ],
            // miss theme_id
            [
                [
                    'genesis_id'     => 6,
                    'genesis_icon'   => 'icon-3.png',
                    'genesis_plural' => 'Managers',
                    'genesis_single' => 'Manager',
                ],
                GenesisException::INVALID_THEME_ID,
            ],
            // theme_id invalid type
            [
                [
                    'genesis_id'     => 6,
                    'theme_id'       => null,
                    'genesis_icon'   => 'icon-3.png',
                    'genesis_plural' => 'Managers',
                    'genesis_single' => 'Manager',
                ],
                GenesisException::INVALID_THEME_ID,
            ],
            // miss genesis_icon
            [
                [
                    'genesis_id'     => 1,
                    'theme_id'       => 1,
                    'genesis_plural' => 'Analysts',
                    'genesis_single' => 'Analyst',
                ],
                GenesisException::INVALID_ICON,
            ],
            // genesis_icon invalid type
            [
                [
                    'genesis_id'     => 1,
                    'theme_id'       => 1,
                    'genesis_icon'   => 100,
                    'genesis_plural' => 'Analysts',
                    'genesis_single' => 'Analyst',
                ],
                GenesisException::INVALID_ICON,
            ],
            // miss genesis_plural
            [
                [
                    'genesis_id'     => 1,
                    'theme_id'       => 1,
                    'genesis_icon'   => 'icon-1.png',
                    'genesis_single' => 'Analyst',
                ],
                GenesisException::INVALID_PLURAL,
            ],
            // genesis_plural invalid type
            [
                [
                    'genesis_id'     => 1,
                    'theme_id'       => 1,
                    'genesis_icon'   => 'icon-1.png',
                    'genesis_plural' => ['Analysts'],
                    'genesis_single' => 'Analyst',
                ],
                GenesisException::INVALID_PLURAL,
            ],
            // miss genesis_single
            [
                [
                    'genesis_id'     => 1,
                    'theme_id'       => 1,
                    'genesis_icon'   => 'icon-1.png',
                    'genesis_plural' => 'Analysts',
                ],
                GenesisException::INVALID_SINGLE,
            ],
            // genesis_single invalid type
            [
                [
                    'genesis_id'     => 1,
                    'theme_id'       => 1,
                    'genesis_icon'   => 'icon-1.png',
                    'genesis_plural' => 'Analysts',
                    'genesis_single' => true,
                ],
                GenesisException::INVALID_SINGLE,
            ],
        ];
    }
}
