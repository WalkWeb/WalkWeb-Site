<?php

declare(strict_types=1);

namespace Test\src\Domain\Community;

use App\Domain\Community\CommunityCollectionFactory;
use App\Domain\Community\CommunityException;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class CommunityCollectionFactoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $data
     * @throws AppException
     */
    public function testCommunityCollectionFactoryCreateSuccess(array $data): void
    {
        $collection = CommunityCollectionFactory::create($data);

        self::assertSameSize($data, $collection);

        $i = 0;
        foreach ($collection as $community) {
            self::assertEquals($data[$i]['id'], $community->getId());
            self::assertEquals($data[$i]['level'], $community->getLevel());
            self::assertEquals($data[$i]['name'], $community->getName());
            self::assertEquals($data[$i]['slug'], $community->getSlug());
            self::assertEquals($data[$i]['description'], $community->getDescription());
            self::assertEquals($data[$i]['icon'], $community->getIcon());
            self::assertEquals($data[$i]['icon_small'], $community->getIconSmall());
            self::assertEquals($data[$i]['head_image'], $community->getHeadImage());
            self::assertEquals($data[$i]['followers'], $community->getFollowers());
            self::assertEquals($data[$i]['fixed_post_id'], $community->getFixedPostId());
            self::assertEquals($data[$i]['menu'], $community->getMenu());
            self::assertEquals($data[$i]['owner_id'], $community->getOwnerId());
            self::assertEquals($data[$i]['total_post_count'], $community->getTotalPostCount());
            self::assertEquals($data[$i]['silver_post_count'], $community->getSilverPostCount());
            self::assertEquals($data[$i]['gold_post_count'], $community->getGoldPostCount());
            self::assertEquals($data[$i]['diamond_post_count'], $community->getDiamondPostCount());
            self::assertEquals($data[$i]['total_comment_count'], $community->getTotalCommentCount());
            self::assertEquals($data[$i]['created_at'], $community->getCreatedAt()->format(self::DATE_FORMAT));
            self::assertEquals($data[$i]['updated_at'], $community->getUpdatedAt()->format(self::DATE_FORMAT));
            $i++;
        }
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     */
    public function testCommunityCollectionFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        CommunityCollectionFactory::create($data);
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
                        'id'                  => '606f332b-4551-4570-842b-498b5d334692',
                        'level'               => 4,
                        'name'                => 'Name Community',
                        'slug'                => 'name-community',
                        'description'         => 'description community',
                        'icon'                => 'icon.png',
                        'icon_small'          => 'icon_small.png',
                        'head_image'          => 'head_image.png',
                        'followers'           => 312,
                        'fixed_post_id'       => '402d565b-94aa-40c9-bdd6-79334f7bafad',
                        'menu'                => '["menu-1", "menu-2", "menu-3"]',
                        'owner_id'            => '4a9820ea-adea-432f-b106-f62ad10b3a03',
                        'total_post_count'    => 54,
                        'silver_post_count'   => 13,
                        'gold_post_count'     => 5,
                        'diamond_post_count'  => 1,
                        'total_comment_count' => 1236,
                        'created_at'          => '2024-06-21 15:30:00',
                        'updated_at'          => '2024-06-21 15:30:00',
                    ],
                    [
                        'id'                  => '606f332b-4551-4570-842b-498b5d334693',
                        'level'               => 4,
                        'name'                => 'Name Community',
                        'slug'                => 'name-community',
                        'description'         => 'description community',
                        'icon'                => 'icon.png',
                        'icon_small'          => 'icon_small.png',
                        'head_image'          => 'head_image.png',
                        'followers'           => 312,
                        'fixed_post_id'       => null,
                        'menu'                => null,
                        'owner_id'            => '4a9820ea-adea-432f-b106-f62ad10b3a03',
                        'total_post_count'    => 54,
                        'silver_post_count'   => 13,
                        'gold_post_count'     => 5,
                        'diamond_post_count'  => 1,
                        'total_comment_count' => 1236,
                        'created_at'          => '2024-06-21 15:30:00',
                        'updated_at'          => '2024-06-21 15:30:00',
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
                        'id'                  => '606f332b-4551-4570-842b-498b5d334692',
                        'level'               => 4,
                        'name'                => 'Name Community',
                        'slug'                => 'name-community',
                        'description'         => 'description community',
                        'icon'                => 'icon.png',
                        'icon_small'          => 'icon_small.png',
                        'head_image'          => 'head_image.png',
                        'followers'           => 312,
                        'fixed_post_id'       => '402d565b-94aa-40c9-bdd6-79334f7bafad',
                        'menu'                => '["menu-1", "menu-2", "menu-3"]',
                        'owner_id'            => '4a9820ea-adea-432f-b106-f62ad10b3a03',
                        'total_post_count'    => 54,
                        'silver_post_count'   => 13,
                        'gold_post_count'     => 5,
                        'diamond_post_count'  => 1,
                        'total_comment_count' => 1236,
                        'created_at'          => '2024-06-21 15:30:00',
                        'updated_at'          => '2024-06-21 15:30:00',
                    ],
                    [
                        'id'                  => '606f332b-4551-4570-842b-498b5d334692',
                        'level'               => 4,
                        'name'                => 'Name Community',
                        'slug'                => 'name-community',
                        'description'         => 'description community',
                        'icon'                => 'icon.png',
                        'icon_small'          => 'icon_small.png',
                        'head_image'          => 'head_image.png',
                        'followers'           => 312,
                        'fixed_post_id'       => null,
                        'menu'                => null,
                        'owner_id'            => '4a9820ea-adea-432f-b106-f62ad10b3a03',
                        'total_post_count'    => 54,
                        'silver_post_count'   => 13,
                        'gold_post_count'     => 5,
                        'diamond_post_count'  => 1,
                        'total_comment_count' => 1236,
                        'created_at'          => '2024-06-21 15:30:00',
                        'updated_at'          => '2024-06-21 15:30:00',
                    ],
                ],
                CommunityException::ALREADY_EXIST,
            ],
            // no array data
            [
                [
                    [
                        'id'                  => '606f332b-4551-4570-842b-498b5d334692',
                        'level'               => 4,
                        'name'                => 'Name Community',
                        'slug'                => 'name-community',
                        'description'         => 'description community',
                        'icon'                => 'icon.png',
                        'icon_small'          => 'icon_small.png',
                        'head_image'          => 'head_image.png',
                        'followers'           => 312,
                        'fixed_post_id'       => '402d565b-94aa-40c9-bdd6-79334f7bafad',
                        'menu'                => '["menu-1", "menu-2", "menu-3"]',
                        'owner_id'            => '4a9820ea-adea-432f-b106-f62ad10b3a03',
                        'total_post_count'    => 54,
                        'silver_post_count'   => 13,
                        'gold_post_count'     => 5,
                        'diamond_post_count'  => 1,
                        'total_comment_count' => 1236,
                        'created_at'          => '2024-06-21 15:30:00',
                        'updated_at'          => '2024-06-21 15:30:00',
                    ],
                    'data',
                ],
                CommunityException::EXPECTED_ARRAY,
            ],
        ];
    }
}
