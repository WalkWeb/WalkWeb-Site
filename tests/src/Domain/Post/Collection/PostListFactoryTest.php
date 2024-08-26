<?php

declare(strict_types=1);

namespace Test\src\Domain\Post\Collection;

use App\Domain\Post\Collection\PostListFactory;
use App\Domain\Post\PostException;
use App\Domain\Post\PostInterface;
use App\Domain\Post\Rating\Rating;
use DateTime;
use Exception;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class PostListFactoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $data
     * @throws Exception
     */
    public function testPostListFactoryCreateSuccess(array $data): void
    {
        $post = PostListFactory::create($data);

        self::assertEquals($data['id'], $post->getId());
        self::assertEquals($data['title'], $post->getTitle());
        self::assertEquals($data['slug'], $post->getSlug());
        self::assertEquals($data['html_content'], $post->getHtmlContent());
        self::assertEquals(new Rating($data['likes'], $data['dislikes'], $data['user_reaction']), $post->getRating());
        self::assertEquals($data['comments_count'], $post->getCommentCount());
        self::assertEquals($data['tags'], $post->getTags());
        self::assertEquals($data['is_liked'], $post->isLiked());
        self::assertEquals($data['author_name'], $post->getAuthorName());
        self::assertEquals($data['author_name'], $post->getAuthorName());
        self::assertEquals($data['community_slug'] ?? '', $post->getCommunitySlug());
        self::assertEquals($data['community_name'] ?? '', $post->getCommunityName());
        self::assertEquals(new DateTime($data['created_at']), $post->getCreatedAt());
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     */
    public function testPostListFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        PostListFactory::create($data);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => 'diablo-2-wiki',
                    'community_name' => 'Diablo 2: Wiki',
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
            ],
            // nullable community_slug and community_name
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
            ],
        ];
    }

    /**
     * @return array
     * @throws AppException
     */
    public function failDataProvider(): array
    {
        return [
            // miss id
            [
                [
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_ID,
            ],
            // id invalid type
            [
                [
                    'id'             => 123,
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_ID,
            ],
            // id invalid uuid
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_ID,
            ],
            // miss title
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_TITLE,
            ],
            // title invalid type
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => null,
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_TITLE,
            ],
            // title over min length
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => self::generateString(PostInterface::TITLE_MIN_LENGTH - 1),
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_TITLE_LENGTH . PostInterface::TITLE_MIN_LENGTH . '-' . PostInterface::TITLE_MAX_LENGTH,
            ],
            // title over max length
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => self::generateString(PostInterface::TITLE_MAX_LENGTH + 1),
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_TITLE_LENGTH . PostInterface::TITLE_MIN_LENGTH . '-' . PostInterface::TITLE_MAX_LENGTH,
            ],
            // miss slug
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_SLUG,
            ],
            // slug invalid type
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 1.6,
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_SLUG,
            ],
            // slug over min length
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => self::generateString(PostInterface::SLUG_MIN_LENGTH - 1),
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_SLUG_LENGTH . PostInterface::SLUG_MIN_LENGTH . '-' . PostInterface::SLUG_MAX_LENGTH,
            ],
            // slug over max length
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => self::generateString(PostInterface::SLUG_MAX_LENGTH + 1),
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_SLUG_LENGTH . PostInterface::SLUG_MIN_LENGTH . '-' . PostInterface::SLUG_MAX_LENGTH,
            ],
            // miss html_content
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_HTML_CONTENT,
            ],
            // html_content invalid type
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => true,
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_HTML_CONTENT,
            ],
            // html_content over min length
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => self::generateString(PostInterface::HTML_CONTENT_MIN_LENGTH - 1),
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_HTML_CONTENT_LENGTH . PostInterface::HTML_CONTENT_MIN_LENGTH . '-' . PostInterface::HTML_CONTENT_MAX_LENGTH,
            ],
            // html_content over max length
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => self::generateString(PostInterface::HTML_CONTENT_MAX_LENGTH + 1),
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_HTML_CONTENT_LENGTH . PostInterface::HTML_CONTENT_MIN_LENGTH . '-' . PostInterface::HTML_CONTENT_MAX_LENGTH,
            ],
            // miss comments_count
            [
                [
                    'id'            => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'         => 'Title',
                    'slug'          => 'title-slug',
                    'html_content'  => '<p>Post content</p>',
                    'likes'         => 12,
                    'dislikes'      => 2,
                    'user_reaction' => 1,
                    'tags'          => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'      => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'   => 'Name',
                    'created_at'    => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_COMMENTS_COUNT,
            ],
            // comments_count invalid type
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => '3',
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_COMMENTS_COUNT,
            ],
            // miss tags
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_TINY_TAGS,
            ],
            // tags invalid type
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => null,
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_TINY_TAGS,
            ],
            // tags no array[array]
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => ['tag-1', 'tag-2'],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_TAG_DATA,
            ],
            // miss tags[slug]
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_TAG_SLUG,
            ],
            // tags[slug] invalid type
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 123,
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_TAG_SLUG,
            ],
            // miss tags[name]
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_TAG_NAME,
            ],
            // tags[name] invalid type
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => null,
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_TAG_NAME,
            ],
            // miss is_liked
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'author_name'    => 'Name',
                    'community_slug' => null,
                    'community_name' => null,
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_IS_LIKED,
            ],
            // is_liked invalid type
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => 1,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_IS_LIKED,
            ],
            // miss author_name
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_AUTHOR_NAME,
            ],
            // author_name invalid type
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'author_name'    => null,
                    'community_slug' => null,
                    'community_name' => null,
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_AUTHOR_NAME,
            ],
            // miss created_at
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                ],
                PostException::INVALID_CREATED_AT,
            ],
            // created_at invalid type
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'author_name'    => 'Name',
                    'community_slug' => null,
                    'community_name' => null,
                    'created_at'     => 123123,
                ],
                PostException::INVALID_CREATED_AT,
            ],
            // created_at invalid date
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => null,
                    'community_name' => null,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-99-99 19:05:19',
                ],
                PostException::INVALID_CREATED_AT,
            ],
            // miss community_slug
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_name' => 'Diablo 2: Wiki',
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_COMMUNITY_SLUG,
            ],
            // community_slug invalid type
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => 123,
                    'community_name' => 'Diablo 2: Wiki',
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_COMMUNITY_SLUG,
            ],
            // miss community_name
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => 'diablo-2-wiki',
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_COMMUNITY_NAME,
            ],
            // community_name invalid type
            [
                [
                    'id'             => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'          => 'Title',
                    'slug'           => 'title-slug',
                    'html_content'   => '<p>Post content</p>',
                    'likes'          => 12,
                    'dislikes'       => 2,
                    'user_reaction'  => 1,
                    'comments_count' => 3,
                    'tags'           => [
                        [
                            'slug' => 'news-100',
                            'name' => 'news',
                        ],
                        [
                            'slug' => 'it-200',
                            'name' => 'it',
                        ],
                    ],
                    'is_liked'       => true,
                    'community_slug' => 'diablo-2-wiki',
                    'community_name' => true,
                    'author_name'    => 'Name',
                    'created_at'     => '2019-08-12 19:05:19',
                ],
                PostException::INVALID_COMMUNITY_NAME,
            ],
        ];
    }
}
