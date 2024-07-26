<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Character\Avatar;

use App\Domain\Account\AccountException;
use App\Domain\Account\Character\Avatar\AvatarException;
use App\Domain\Account\Character\Avatar\AvatarFactory;
use App\Domain\Account\Character\Genesis\GenesisFactory;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class AvatarFactoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $data
     * @throws AccountException
     * @throws AppException
     */
    public function testAvatarFactoryCreateSuccess(array $data): void
    {
        $avatar = AvatarFactory::create($data);

        self::assertEquals($data['avatar_id'], $avatar->getId());
        self::assertEquals($data['floor_id'], $avatar->getFloor()->getId());
        self::assertEquals($data['origin_url'], $avatar->getOriginUrl());
        self::assertEquals($data['small_url'], $avatar->getSmallUrl());
        self::assertEquals(GenesisFactory::create($data), $avatar->getGenesis());
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     * @throws AccountException
     */
    public function testAvatarFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        AvatarFactory::create($data);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    'avatar_id'      => 12,
                    'floor_id'       => 1,
                    'origin_url'     => 'origin.png',
                    'small_url'      => 'small.png',
                    'genesis_id'     => 1,
                    'theme_id'       => 1,
                    'genesis_icon'   => 'icon-1.png',
                    'genesis_plural' => 'Analysts',
                    'genesis_single' => 'Analyst',
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
            // miss avatar_id
            [
                [
                    'floor_id'       => 1,
                    'origin_url'     => 'origin.png',
                    'small_url'      => 'small.png',
                    'genesis_id'     => 1,
                    'theme_id'       => 1,
                    'genesis_icon'   => 'icon-1.png',
                    'genesis_plural' => 'Analysts',
                    'genesis_single' => 'Analyst',
                ],
                AvatarException::INVALID_ID,
            ],
            // avatar_id invalid type
            [
                [
                    'avatar_id'      => null,
                    'floor_id'       => 1,
                    'origin_url'     => 'origin.png',
                    'small_url'      => 'small.png',
                    'genesis_id'     => 1,
                    'theme_id'       => 1,
                    'genesis_icon'   => 'icon-1.png',
                    'genesis_plural' => 'Analysts',
                    'genesis_single' => 'Analyst',
                ],
                AvatarException::INVALID_ID,
            ],
            // miss floor_id
            [
                [
                    'avatar_id'      => 12,
                    'origin_url'     => 'origin.png',
                    'small_url'      => 'small.png',
                    'genesis_id'     => 1,
                    'theme_id'       => 1,
                    'genesis_icon'   => 'icon-1.png',
                    'genesis_plural' => 'Analysts',
                    'genesis_single' => 'Analyst',
                ],
                AvatarException::INVALID_FLOOR_ID,
            ],
            // floor_id invalid type
            [
                [
                    'avatar_id'      => 12,
                    'floor_id'       => 'male',
                    'origin_url'     => 'origin.png',
                    'small_url'      => 'small.png',
                    'genesis_id'     => 1,
                    'theme_id'       => 1,
                    'genesis_icon'   => 'icon-1.png',
                    'genesis_plural' => 'Analysts',
                    'genesis_single' => 'Analyst',
                ],
                AvatarException::INVALID_FLOOR_ID,
            ],
            // miss origin_url
            [
                [
                    'avatar_id'      => 12,
                    'floor_id'       => 1,
                    'small_url'      => 'small.png',
                    'genesis_id'     => 1,
                    'theme_id'       => 1,
                    'genesis_icon'   => 'icon-1.png',
                    'genesis_plural' => 'Analysts',
                    'genesis_single' => 'Analyst',
                ],
                AvatarException::INVALID_ORIGIN_URL,
            ],
            // origin_url invalid type
            [
                [
                    'avatar_id'      => 12,
                    'floor_id'       => 1,
                    'origin_url'     => ['origin.png'],
                    'small_url'      => 'small.png',
                    'genesis_id'     => 1,
                    'theme_id'       => 1,
                    'genesis_icon'   => 'icon-1.png',
                    'genesis_plural' => 'Analysts',
                    'genesis_single' => 'Analyst',
                ],
                AvatarException::INVALID_ORIGIN_URL,
            ],
            // miss small_url
            [
                [
                    'avatar_id'      => 12,
                    'floor_id'       => 1,
                    'origin_url'     => 'origin.png',
                    'genesis_id'     => 1,
                    'theme_id'       => 1,
                    'genesis_icon'   => 'icon-1.png',
                    'genesis_plural' => 'Analysts',
                    'genesis_single' => 'Analyst',
                ],
                AvatarException::INVALID_SMALL_URL,
            ],
            // small_url invalid type
            [
                [
                    'avatar_id'      => 12,
                    'floor_id'       => 1,
                    'origin_url'     => 'origin.png',
                    'small_url'      => true,
                    'genesis_id'     => 1,
                    'theme_id'       => 1,
                    'genesis_icon'   => 'icon-1.png',
                    'genesis_plural' => 'Analysts',
                    'genesis_single' => 'Analyst',
                ],
                AvatarException::INVALID_SMALL_URL,
            ],
        ];
    }
}
