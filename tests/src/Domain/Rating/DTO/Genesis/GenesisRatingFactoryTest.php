<?php

declare(strict_types=1);

namespace Test\src\Domain\Rating\DTO\Genesis;

use App\Domain\Rating\DTO\Genesis\GenesisRatingException;
use App\Domain\Rating\DTO\Genesis\GenesisRatingFactory;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class GenesisRatingFactoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $data
     * @throws AppException
     */
    public function testGenesisRatingFactoryCreateSuccess(array $data): void
    {
        $genesis = GenesisRatingFactory::create($data);

        self::assertEquals($data['id'], $genesis->getId());
        self::assertEquals($data['icon'], $genesis->getIcon());
        self::assertEquals($data['name'], $genesis->getName());

        if ($data['member_count'] === null) {
            self::assertEquals(0, $genesis->getMemberCount());
        } else {
            self::assertEquals($data['member_count'], $genesis->getMemberCount());
        }

        if ($data['post_count'] === null) {
            self::assertEquals(0, $genesis->getPostCount());
        } else {
            self::assertEquals($data['post_count'], $genesis->getPostCount());
        }

        if ($data['comment_count'] === null) {
            self::assertEquals(0, $genesis->getCommentCount());
        } else {
            self::assertEquals($data['comment_count'], $genesis->getCommentCount());
        }

        if ($data['carma_count'] === null) {
            self::assertEquals(0, $genesis->getCarmaCount());
        } else {
            self::assertEquals($data['carma_count'], $genesis->getCarmaCount());
        }
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     */
    public function testGenesisRatingFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        GenesisRatingFactory::create($data);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    'id'            => 1,
                    'icon'          => 'icon.png',
                    'name'          => 'Designers',
                    'member_count'  => 10,
                    'post_count'    => 20,
                    'comment_count' => 30,
                    'carma_count'   => 40,
                ],
            ],
            [
                [
                    'id'            => 1,
                    'icon'          => 'icon.png',
                    'name'          => 'Designers',
                    'member_count'  => null,
                    'post_count'    => null,
                    'comment_count' => null,
                    'carma_count'   => null,
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
                    'icon'          => 'icon.png',
                    'name'          => 'Designers',
                    'member_count'  => 10,
                    'post_count'    => 20,
                    'comment_count' => 30,
                    'carma_count'   => 40,
                ],
                GenesisRatingException::INVALID_ID,
            ],
            // id invalid type
            [
                [
                    'id'            => '1b9384b6-46c3-48e8-86c3-6b1dde8f7803',
                    'icon'          => 'icon.png',
                    'name'          => 'Designers',
                    'member_count'  => 10,
                    'post_count'    => 20,
                    'comment_count' => 30,
                    'carma_count'   => 40,
                ],
                GenesisRatingException::INVALID_ID,
            ],
            // miss icon
            [
                [
                    'id'            => 1,
                    'name'          => 'Designers',
                    'member_count'  => 10,
                    'post_count'    => 20,
                    'comment_count' => 30,
                    'carma_count'   => 40,
                ],
                GenesisRatingException::INVALID_ICON,
            ],
            // icon invalid type
            [
                [
                    'id'            => 1,
                    'icon'          => null,
                    'name'          => 'Designers',
                    'member_count'  => 10,
                    'post_count'    => 20,
                    'comment_count' => 30,
                    'carma_count'   => 40,
                ],
                GenesisRatingException::INVALID_ICON,
            ],
            // miss name
            [
                [
                    'id'            => 1,
                    'icon'          => 'icon.png',
                    'member_count'  => 10,
                    'post_count'    => 20,
                    'comment_count' => 30,
                    'carma_count'   => 40,
                ],
                GenesisRatingException::INVALID_NAME,
            ],
            // name invalid type
            [
                [
                    'id'            => 1,
                    'icon'          => 'icon.png',
                    'name'          => null,
                    'member_count'  => 10,
                    'post_count'    => 20,
                    'comment_count' => 30,
                    'carma_count'   => 40,
                ],
                GenesisRatingException::INVALID_NAME,
            ],
            // miss member_count
            [
                [
                    'id'            => 1,
                    'icon'          => 'icon.png',
                    'name'          => 'Designers',
                    'post_count'    => 20,
                    'comment_count' => 30,
                    'carma_count'   => 40,
                ],
                GenesisRatingException::INVALID_MEMBER_COUNT,
            ],
            // member_count invalid type
            [
                [
                    'id'            => 1,
                    'icon'          => 'icon.png',
                    'name'          => 'Designers',
                    'member_count'  => '10',
                    'post_count'    => 20,
                    'comment_count' => 30,
                    'carma_count'   => 40,
                ],
                GenesisRatingException::INVALID_MEMBER_COUNT,
            ],
            // miss post_count
            [
                [
                    'id'            => 1,
                    'icon'          => 'icon.png',
                    'name'          => 'Designers',
                    'member_count'  => 10,
                    'comment_count' => 30,
                    'carma_count'   => 40,
                ],
                GenesisRatingException::INVALID_POST_COUNT,
            ],
            // post_count invalid type
            [
                [
                    'id'            => 1,
                    'icon'          => 'icon.png',
                    'name'          => 'Designers',
                    'member_count'  => 10,
                    'post_count'    => [20],
                    'comment_count' => 30,
                    'carma_count'   => 40,
                ],
                GenesisRatingException::INVALID_POST_COUNT,
            ],
            // miss comment_count
            [
                [
                    'id'            => 1,
                    'icon'          => 'icon.png',
                    'name'          => 'Designers',
                    'member_count'  => 10,
                    'post_count'    => 20,
                    'carma_count'   => 40,
                ],
                GenesisRatingException::INVALID_COMMENT_COUNT,
            ],
            // comment_count invalid type
            [
                [
                    'id'            => 1,
                    'icon'          => 'icon.png',
                    'name'          => 'Designers',
                    'member_count'  => 10,
                    'post_count'    => 20,
                    'comment_count' => true,
                    'carma_count'   => 40,
                ],
                GenesisRatingException::INVALID_COMMENT_COUNT,
            ],
            // miss carma_count
            [
                [
                    'id'            => 1,
                    'icon'          => 'icon.png',
                    'name'          => 'Designers',
                    'member_count'  => 10,
                    'post_count'    => 20,
                    'comment_count' => 30,
                ],
                GenesisRatingException::INVALID_CARMA_COUNT,
            ],
            // carma_count invalid type
            [
                [
                    'id'            => 1,
                    'icon'          => 'icon.png',
                    'name'          => 'Designers',
                    'member_count'  => 10,
                    'post_count'    => 20,
                    'comment_count' => 30,
                    'carma_count'   => '40',
                ],
                GenesisRatingException::INVALID_CARMA_COUNT,
            ],
        ];
    }
}
