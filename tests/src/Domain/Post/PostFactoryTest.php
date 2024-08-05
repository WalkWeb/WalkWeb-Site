<?php

declare(strict_types=1);

namespace Test\src\Domain\Post;

use App\Domain\Post\Tag\TagFactory;
use DateTime;
use Exception;
use App\Domain\Post\Author\AuthorFactory;
use App\Domain\Post\PostException;
use App\Domain\Post\PostFactory;
use App\Domain\Post\PostInterface;
use App\Domain\Post\Rating\RatingFactory;
use App\Domain\Post\Status\StatusInterface;
use App\Domain\Post\Tag\TagCollection;
use Test\AbstractTest;

class PostFactoryTest extends AbstractTest
{
    /**
     * Тест на успешное создание объекта поста на основе массива данных
     *
     * @dataProvider successDataProvider
     * @param array $data
     * @throws Exception
     */
    public function testPostFactoryCreateSuccess(array $data): void
    {
        $post = PostFactory::create($data);

        self::assertEquals($data['id'], $post->getId());
        self::assertEquals(htmlspecialchars($data['title']), $post->getTitle());
        self::assertEquals($data['slug'], $post->getSlug());
        self::assertEquals(htmlspecialchars($data['content']), $post->getContent());
        self::assertEquals(AuthorFactory::create($data), $post->getAuthor());
        self::assertEquals(RatingFactory::create($data), $post->getRating());
        self::assertEquals($data['comments_count'], $post->getCommentsCount());
        self::assertEquals((bool)$data['published'], $post->isPublished());
        self::assertEquals(new DateTime($data['created_at']), $post->getCreatedAt());

        self::assertEquals($this->createTags($data), $post->getTags());
        self::assertSameSize($data['tags'], $post->getTags());

        if (!is_null($data['updated_at'])) {
            self::assertEquals(new DateTime($data['updated_at']), $post->getUpdatedAt());
        } else {
            self::assertNull($post->getUpdatedAt());
        }

        self::assertEquals(
            [
                "id"               => $data['id'],
                "title"            => $data['title'],
                "slug"             => $data['slug'],
                "content"          => $data['content'],
                "status_id"        => $data['status_id'],
                "likes"            => $data['likes'],
                "dislikes"         => $data['dislikes'],
                "user_reaction"    => $data['user_reaction'],
                "comments_count"   => $data['comments_count'],
                "published"        => $data['published'],
                "is_liked"         => $data['is_liked'],
                "tags"             => $data['tags'],
                "author_id"        => $data['author_id'],
                "author_name"      => $data['author_name'],
                "author_avatar"    => $data['author_avatar'],
                "author_level"     => $data['author_level'],
                "author_status_id" => $data['author_status_id'],
                "created_at"       => $data['created_at'],
                "updated_at"       => $data['updated_at'],
            ],
            $post->toArray()
        );
    }

    /**
     * Тесты на различные варианты невалидных данных
     *
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     */
    public function testPostFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage($error);
        PostFactory::create($data);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                // Без тегов и updated_at = null
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'is_liked'         => true,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                ],
            ],
            [
                // Без тегов и updated_at != null
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::SILVER,
                    'likes'            => 10,
                    'dislikes'         => -5,
                    'user_reaction'    => 0,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'is_liked'         => false,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'created_at'       => '2019-08-12 19:00:00',
                    'updated_at'       => '2019-08-15 20:20:00',
                ],
            ],
            [
                // С тегами и updated_at = null
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::GOLD,
                    'likes'            => 1,
                    'dislikes'         => -6,
                    'user_reaction'    => -1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'is_liked'         => true,
                    'tags'             => [
                        [
                            'id'              => '83d9fb1c-c417-4528-8745-adfd0af24f2c',
                            'name'            => 'новости',
                            'slug'            => 'novosti',
                            'icon'            => 'icon-1.png',
                            'preview_post_id' => '9ee22e72-13f3-4675-a612-d28844b43f40',
                            'approved'        => true,
                        ],
                        [
                            'id'              => '3bf4f5b2-d79c-45c6-b6c3-7f8dee8bf8a5',
                            'name'            => 'статьи',
                            'slug'            => 'stati',
                            'icon'            => 'icon-2.png',
                            'preview_post_id' => '',
                            'approved'        => false,
                        ],
                    ],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                ],
            ],
            [
                // Со спецсимволами в title и content
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => '<b>Title</b>',
                    'slug'             => 'title-slug',
                    'content'          => '<h1>Post content</h1>',
                    'status_id'        => StatusInterface::DIAMOND,
                    'likes'            => 0,
                    'dislikes'         => 0,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'is_liked'         => true,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                ],
            ],
        ];
    }

    /**
     * @return array
     * @throws Exception
     */
    public function failDataProvider(): array
    {
        return [
            // id
            [
                // отсутствует id
                [
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_ID,
            ],
            [
                // id некорректного типа
                [
                    'id'               => 10,
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_ID,
            ],

            // title
            [
                // отсутствует title
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_TITLE,
            ],
            [
                // title некорректного типа
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => ['Title'],
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_TITLE,
            ],
            [
                // title короче минимальной длины
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => self::generateString(PostInterface::TITLE_MIN_LENGTH - 1),
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_TITLE_VALUE . PostInterface::TITLE_MIN_LENGTH . '-' . PostInterface::TITLE_MAX_LENGTH,
            ],
            [
                // title длиннее максимальной длины
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => self::generateString(PostInterface::TITLE_MAX_LENGTH + 1),
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_TITLE_VALUE . PostInterface::TITLE_MIN_LENGTH . '-' . PostInterface::TITLE_MAX_LENGTH,
            ],

            // slug
            [
                // отсутствует slug
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_SLUG,
            ],
            [
                // slug некорректного типа
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 10.4,
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_SLUG,
            ],

            // content
            [
                // отсутствует content
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_CONTENT,
            ],
            [
                // content некорректного типа
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => false,
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_CONTENT,
            ],
            [
                // content короче минимальной длины
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => self::generateString(PostInterface::CONTENT_MIN_LENGTH - 1),
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_CONTENT_VALUE . PostInterface::CONTENT_MIN_LENGTH . '-' . PostInterface::CONTENT_MAX_LENGTH,
            ],
            [
                // content длиннее максимальной длины
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => self::generateString(PostInterface::CONTENT_MAX_LENGTH + 1),
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_CONTENT_VALUE . PostInterface::CONTENT_MIN_LENGTH . '-' . PostInterface::CONTENT_MAX_LENGTH,
            ],

            [
                // отсутствует status
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_STATUS_ID,
            ],
            [
                // status некорректного типа
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => '1',
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_STATUS_ID,
            ],

            // comments_count
            [
                // отсутствует comments_count
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_COMMENTS_COUNT,
            ],
            [
                // comments_count некорректного типа
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => '3',
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_COMMENTS_COUNT,
            ],

            // published
            [
                // отсутствует published
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_PUBLISHED,
            ],
            [
                // published некорректного типа
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => true, // не смотря на тип bool у объекта, из базы ожидается получить int
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_PUBLISHED,
            ],

            // created_at
            [
                // отсутствует created_at
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'updated_at'       => null,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_CREATED_AT,
            ],
            [
                // created_at некорректного типа
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => [],
                    'updated_at'       => null,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_CREATED_AT,
            ],
            [
                // created_at некорректного значения даты
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '9999-99-99 99:99:99',
                    'updated_at'       => null,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_CREATED_AT,
            ],

            // updated_at
            [
                // отсутствует updated_at
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_UPDATED_AT,
            ],
            [
                // updated_at некорректного типа
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => 'null',
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_UPDATED_AT,
            ],
            [
                // updated_at некорректного значения даты
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => '9999-99-99 99:99:99',
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_UPDATED_AT,
            ],

            // tags
            [
                // отсутствует tags
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_TAGS,
            ],
            [
                // tags некорректного типа
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'tags'             => null,
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_TAGS,
            ],
            [
                // tags содержит не массивы
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'tags'             => ['tag'],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                ],
                PostException::INVALID_TAGS_DATA,
            ],
            [
                // отсутствует is_liked
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                ],
                PostException::INVALID_IS_LIKED_DATA,
            ],
            [
                // is_liked некорректного типа
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => 'Post content',
                    'status_id'        => StatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'tags'             => [],
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => 123,
                ],
                PostException::INVALID_IS_LIKED_DATA,
            ],

            // Проверка валидации параметров автора сделана в AuthorFactoryTest
        ];
    }

    /**
     * @param array $data
     * @return TagCollection
     * @throws Exception
     */
    private function createTags(array $data): TagCollection
    {
        $collection = new TagCollection();

        foreach ($data['tags'] as $tagData) {
            $collection->add(TagFactory::create($tagData));
        }

        return $collection;
    }
}
