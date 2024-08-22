<?php

declare(strict_types=1);

namespace Test\src\Domain\Rating\DTO\Genesis;

use App\Domain\Rating\DTO\Genesis\GenesisRatingCollectionFactory;
use App\Domain\Rating\DTO\Genesis\GenesisRatingException;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class GenesisRatingCollectionFactoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $data
     * @throws AppException
     */
    public function testGenesisRatingCollectionFactoryCreateSuccess(array $data): void
    {
        $collections = GenesisRatingCollectionFactory::create($data);

        self::assertSameSize($data, $collections);

        $i = 0;
        foreach ($collections as $genesis) {
            self::assertEquals($data[$i]['id'], $genesis->getId());
            self::assertEquals($data[$i]['icon'], $genesis->getIcon());
            self::assertEquals($data[$i]['name'], $genesis->getName());

            if ($data[$i]['member_count'] === null) {
                self::assertEquals(0, $genesis->getMemberCount());
            } else {
                self::assertEquals($data[$i]['member_count'], $genesis->getMemberCount());
            }

            if ($data[$i]['post_count'] === null) {
                self::assertEquals(0, $genesis->getPostCount());
            } else {
                self::assertEquals($data[$i]['post_count'], $genesis->getPostCount());
            }

            if ($data[$i]['comment_count'] === null) {
                self::assertEquals(0, $genesis->getCommentCount());
            } else {
                self::assertEquals($data[$i]['comment_count'], $genesis->getCommentCount());
            }

            if ($data[$i]['carma_count'] === null) {
                self::assertEquals(0, $genesis->getCarmaCount());
            } else {
                self::assertEquals($data[$i]['carma_count'], $genesis->getCarmaCount());
            }

            $i++;
        }
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     */
    public function testGenesisRatingCollectionFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        GenesisRatingCollectionFactory::create($data);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    [
                        'id'            => 1,
                        'icon'          => 'icon-1.png',
                        'name'          => 'Designers',
                        'member_count'  => 10,
                        'post_count'    => '20',
                        'comment_count' => '30',
                        'carma_count'   => '40',
                    ],
                    [
                        'id'            => 2,
                        'icon'          => 'icon-2.png',
                        'name'          => 'Programmers',
                        'member_count'  => null,
                        'post_count'    => null,
                        'comment_count' => null,
                        'carma_count'   => null,
                    ],
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
            // double id
            [
                [
                    [
                        'id'            => 1,
                        'icon'          => 'icon-1.png',
                        'name'          => 'Designers',
                        'member_count'  => 10,
                        'post_count'    => '20',
                        'comment_count' => '30',
                        'carma_count'   => '40',
                    ],
                    [
                        'id'            => 1,
                        'icon'          => 'icon-2.png',
                        'name'          => 'Programmers',
                        'member_count'  => null,
                        'post_count'    => null,
                        'comment_count' => null,
                        'carma_count'   => null,
                    ],
                ],
                GenesisRatingException::ALREADY_EXIST,
            ],
            // no array data
            [
                [
                    [
                        'id'            => 1,
                        'icon'          => 'icon-1.png',
                        'name'          => 'Designers',
                        'member_count'  => 10,
                        'post_count'    => '20',
                        'comment_count' => '30',
                        'carma_count'   => '40',
                    ],
                    'data',
                ],
                GenesisRatingException::EXPECTED_ARRAY,
            ],
        ];
    }
}
