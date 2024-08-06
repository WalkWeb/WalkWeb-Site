<?php

declare(strict_types=1);

namespace Test\src\Domain\Post\Tag;

use App\Domain\Post\Tag\TagCollectionFactory;
use App\Domain\Post\Tag\TagException;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class TagCollectionFactoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $data
     * @throws AppException
     */
    public function testTagCollectionFactoryCreateSuccess(array $data): void
    {
        $collection = TagCollectionFactory::create($data);

        self::assertSameSize($data, $collection);

        $i = 0;
        foreach ($collection as $tag) {
            self::assertEquals($data[$i]['id'], $tag->getId());
            self::assertEquals($data[$i]['name'], $tag->getName());
            self::assertEquals($data[$i]['slug'], $tag->getSlug());
            self::assertEquals($data[$i]['icon'], $tag->getIcon());
            self::assertEquals($data[$i]['preview_post_id'], $tag->getPreviewPostId());
            self::assertEquals($data[$i]['approved'], $tag->isApproved());
            $i++;
        }
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     * @throws AppException
     */
    public function testTagCollectionFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        TagCollectionFactory::create($data);
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
                        'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f21',
                        'name'            => 'tag-1',
                        'slug'            => 'tag-1-slug',
                        'icon'            => 'icon-1.png',
                        'preview_post_id' => '9ee22e72-13f3-4675-a612-d28844b43f40',
                        'approved'        => 1,
                    ],
                    [
                        'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f22',
                        'name'            => 'tag-2',
                        'slug'            => 'tag-2-slug',
                        'icon'            => 'icon-2.png',
                        'preview_post_id' => '',
                        'approved'        => 0,
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
            // already exist
            [
                [
                    [
                        'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f21',
                        'name'            => 'tag-1',
                        'slug'            => 'tag-1-slug',
                        'icon'            => 'icon-1.png',
                        'preview_post_id' => '9ee22e72-13f3-4675-a612-d28844b43f40',
                        'approved'        => 1,
                    ],
                    [
                        'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f21',
                        'name'            => 'tag-1',
                        'slug'            => 'tag-1-slug',
                        'icon'            => 'icon-1.png',
                        'preview_post_id' => '9ee22e72-13f3-4675-a612-d28844b43f40',
                        'approved'        => 1,
                    ],
                ],
                TagException::ALREADY_EXIST,
            ],
            // data no array
            [
                [
                    [
                        'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f21',
                        'name'            => 'tag-1',
                        'slug'            => 'tag-1-slug',
                        'icon'            => 'icon-1.png',
                        'preview_post_id' => '9ee22e72-13f3-4675-a612-d28844b43f40',
                        'approved'        => 1,
                    ],
                    'string',
                ],
                TagException::EXPECTED_ARRAY,
            ],
        ];
    }
}
