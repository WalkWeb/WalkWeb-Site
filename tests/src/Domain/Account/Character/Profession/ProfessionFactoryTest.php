<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Character\Profession;

use App\Domain\Account\Character\Genesis\GenesisFactory;
use App\Domain\Account\Character\Profession\ProfessionException;
use App\Domain\Account\Character\Profession\ProfessionFactory;
use App\Domain\Account\Floor\Floor;
use App\Domain\Account\Floor\FloorInterface;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class ProfessionFactoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $data
     * @throws AppException
     */
    public function testProfessionFactoryCreateSuccess(array $data): void
    {
        $profession = ProfessionFactory::create($data);

        self::assertEquals($data['profession_id'], $profession->getId());
        self::assertEquals($data['profession_icon'], $profession->getIcon());
        self::assertEquals($data['profession_name_male'], $profession->getNameMale());
        self::assertEquals($data['profession_name_female'], $profession->getNameFemale());
        self::assertEquals($data['profession_name_male'], $profession->getName(new Floor(FloorInterface::MALE)));
        self::assertEquals($data['profession_name_female'], $profession->getName(new Floor(FloorInterface::FEMALE)));

        self::assertEquals(GenesisFactory::create($data), $profession->getGenesis());
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     */
    public function testProfessionFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        ProfessionFactory::create($data);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    'profession_id'          => 1,
                    'profession_icon'        => '/img/icon/genesis_default.png',
                    'profession_name_male'   => 'Default',
                    'profession_name_female' => 'Default',

                    'genesis_id'             => 1,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'icon-1.png',
                    'genesis_plural'         => 'Analysts',
                    'genesis_single'         => 'Analyst',
                ],
            ],
            [
                [
                    'profession_id'          => 6,
                    'profession_icon'        => '/img/icon/genesis_default.png',
                    'profession_name_male'   => 'Default-1',
                    'profession_name_female' => 'Default-2',

                    'genesis_id'             => 6,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'icon-3.png',
                    'genesis_plural'         => 'Managers',
                    'genesis_single'         => 'Manager',
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
            // miss profession_id
            [
                [
                    'profession_icon'        => '/img/icon/genesis_default.png',
                    'profession_name_male'   => 'Default',
                    'profession_name_female' => 'Default',

                    'genesis_id'             => 1,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'icon-1.png',
                    'genesis_plural'         => 'Analysts',
                    'genesis_single'         => 'Analyst',
                ],
                ProfessionException::INVALID_ID,
            ],
            // profession_id invalid type
            [
                [
                    'profession_id'          => null,
                    'profession_icon'        => '/img/icon/genesis_default.png',
                    'profession_name_male'   => 'Default',
                    'profession_name_female' => 'Default',

                    'genesis_id'             => 1,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'icon-1.png',
                    'genesis_plural'         => 'Analysts',
                    'genesis_single'         => 'Analyst',
                ],
                ProfessionException::INVALID_ID,
            ],
            // miss profession_icon
            [
                [
                    'profession_id'          => 1,
                    'profession_name_male'   => 'Default',
                    'profession_name_female' => 'Default',

                    'genesis_id'             => 1,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'icon-1.png',
                    'genesis_plural'         => 'Analysts',
                    'genesis_single'         => 'Analyst',
                ],
                ProfessionException::INVALID_ICON,
            ],
            // profession_icon invalid type
            [
                [
                    'profession_id'          => 1,
                    'profession_icon'        => true,
                    'profession_name_male'   => 'Default',
                    'profession_name_female' => 'Default',

                    'genesis_id'             => 1,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'icon-1.png',
                    'genesis_plural'         => 'Analysts',
                    'genesis_single'         => 'Analyst',
                ],
                ProfessionException::INVALID_ICON,
            ],
            // miss profession_name_male
            [
                [
                    'profession_id'          => 1,
                    'profession_icon'        => '/img/icon/genesis_default.png',
                    'profession_name_female' => 'Default',

                    'genesis_id'             => 1,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'icon-1.png',
                    'genesis_plural'         => 'Analysts',
                    'genesis_single'         => 'Analyst',
                ],
                ProfessionException::INVALID_NAME_MALE,
            ],
            // profession_name_male invalid type
            [
                [
                    'profession_id'          => 1,
                    'profession_icon'        => '/img/icon/genesis_default.png',
                    'profession_name_male'   => 10,
                    'profession_name_female' => 'Default',

                    'genesis_id'             => 1,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'icon-1.png',
                    'genesis_plural'         => 'Analysts',
                    'genesis_single'         => 'Analyst',
                ],
                ProfessionException::INVALID_NAME_MALE,
            ],
            // miss profession_name_female
            [
                [
                    'profession_id'          => 1,
                    'profession_icon'        => '/img/icon/genesis_default.png',
                    'profession_name_male'   => 'Default',

                    'genesis_id'             => 1,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'icon-1.png',
                    'genesis_plural'         => 'Analysts',
                    'genesis_single'         => 'Analyst',
                ],
                ProfessionException::INVALID_NAME_FEMALE,
            ],
            // profession_name_female invalid type
            [
                [
                    'profession_id'          => 1,
                    'profession_icon'        => '/img/icon/genesis_default.png',
                    'profession_name_male'   => 'Default',
                    'profession_name_female' => ['Default'],

                    'genesis_id'             => 1,
                    'theme_id'               => 1,
                    'genesis_icon'           => 'icon-1.png',
                    'genesis_plural'         => 'Analysts',
                    'genesis_single'         => 'Analyst',
                ],
                ProfessionException::INVALID_NAME_FEMALE,
            ],
        ];
    }
}
