<?php

declare(strict_types=1);

namespace Test\src\Domain\Post\Collection;

use App\Domain\Post\Collection\PostCollectionFactory;
use App\Domain\Post\PostException;
use App\Domain\Post\Rating\Rating;
use DateTime;
use Exception;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class PostCollectionFactoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $data
     * @throws Exception
     */
    public function testPostCollectionFactoryCreateSuccess(array $data): void
    {
        $collection = PostCollectionFactory::create($data);

        self::assertSameSize($data, $collection);

        $i = 0;
        foreach ($collection as $post) {
            self::assertEquals($data[$i]['id'], $post->getId());
            self::assertEquals($data[$i]['title'], $post->getTitle());
            self::assertEquals($data[$i]['slug'], $post->getSlug());
            self::assertEquals($data[$i]['html_content'], $post->getHtmlContent());
            self::assertEquals(new Rating($data[$i]['likes'], $data[$i]['dislikes'], $data[$i]['user_reaction']), $post->getRating());
            self::assertEquals($data[$i]['comment_count'], $post->getCommentCount());
            self::assertEquals($data[$i]['tags'], $post->getTags());
            self::assertEquals($data[$i]['is_liked'], $post->isLiked());
            self::assertEquals($data[$i]['author_name'], $post->getAuthorName());
            self::assertEquals(new DateTime($data[$i]['created_at']), $post->getCreatedAt());
            $i++;
        }
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     */
    public function testPostCollectionFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        PostCollectionFactory::create($data);
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
                        'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                        'title'            => 'Title',
                        'slug'             => 'title-slug',
                        'html_content'     => '<p>Post content</p>',
                        'likes'            => 12,
                        'dislikes'         => 2,
                        'user_reaction'    => 1,
                        'comment_count'    => 3,
                        'tags'             => [
                            [
                                'slug' => 'news-100',
                                'name' => 'news',
                            ],
                            [
                                'slug' => 'it-200',
                                'name' => 'it',
                            ],
                        ],
                        'is_liked'         => true,
                        'author_name'      => 'Name',
                        'created_at'       => '2019-08-12 19:05:19',
                    ],
                    [
                        'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c6812',
                        'title'            => 'Title 2',
                        'slug'             => 'title-slug-2',
                        'html_content'     => '<p>Post content</p>',
                        'likes'            => 12,
                        'dislikes'         => 2,
                        'user_reaction'    => 1,
                        'comment_count'    => 3,
                        'tags'             => [
                            [
                                'slug' => 'news-100',
                                'name' => 'news',
                            ],
                            [
                                'slug' => 'it-200',
                                'name' => 'it',
                            ],
                        ],
                        'is_liked'         => true,
                        'author_name'      => 'Name',
                        'created_at'       => '2019-08-12 19:05:19',
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
                        'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                        'title'            => 'Title',
                        'slug'             => 'title-slug',
                        'html_content'     => '<p>Post content</p>',
                        'likes'            => 12,
                        'dislikes'         => 2,
                        'user_reaction'    => 1,
                        'comment_count'    => 3,
                        'tags'             => [
                            [
                                'slug' => 'news-100',
                                'name' => 'news',
                            ],
                            [
                                'slug' => 'it-200',
                                'name' => 'it',
                            ],
                        ],
                        'is_liked'         => true,
                        'author_name'      => 'Name',
                        'created_at'       => '2019-08-12 19:05:19',
                    ],
                    [
                        'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                        'title'            => 'Title 2',
                        'slug'             => 'title-slug-2',
                        'html_content'     => '<p>Post content</p>',
                        'likes'            => 12,
                        'dislikes'         => 2,
                        'user_reaction'    => 1,
                        'comment_count'    => 3,
                        'tags'             => [
                            [
                                'slug' => 'news-100',
                                'name' => 'news',
                            ],
                            [
                                'slug' => 'it-200',
                                'name' => 'it',
                            ],
                        ],
                        'is_liked'         => true,
                        'author_name'      => 'Name',
                        'created_at'       => '2019-08-12 19:05:19',
                    ],
                ],
                PostException::ALREADY_EXIST,
            ],
            // no array data
            [
                [
                    'post data',
                    [
                        'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                        'title'            => 'Title 2',
                        'slug'             => 'title-slug-2',
                        'html_content'     => '<p>Post content</p>',
                        'likes'            => 12,
                        'dislikes'         => 2,
                        'user_reaction'    => 1,
                        'comment_count'    => 3,
                        'tags'             => [
                            [
                                'slug' => 'news-100',
                                'name' => 'news',
                            ],
                            [
                                'slug' => 'it-200',
                                'name' => 'it',
                            ],
                        ],
                        'is_liked'         => true,
                        'author_name'      => 'Name',
                        'created_at'       => '2019-08-12 19:05:19',
                    ],
                ],
                PostException::EXPECTED_ARRAY,
            ],
        ];
    }
}
