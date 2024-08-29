<?php

declare(strict_types=1);

namespace Test\src\Domain\Post;

use App\Domain\Post\DTO\CreatePostRequest;
use App\Domain\Post\Status\PostStatus;
use DateTime;
use Exception;
use App\Domain\Post\Author\AuthorFactory;
use App\Domain\Post\PostException;
use App\Domain\Post\PostFactory;
use App\Domain\Post\PostInterface;
use App\Domain\Post\Rating\RatingFactory;
use App\Domain\Post\Status\PostStatusInterface;
use App\Domain\Post\Tag\TagCollection;
use Ramsey\Uuid\Uuid;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class PostFactoryTest extends AbstractTest
{
    /**
     * Тест на успешное создание объекта поста на основе массива данных
     *
     * @dataProvider successDataProvider
     * @param array $data
     * @param TagCollection $tags
     * @throws Exception
     */
    public function testPostFactoryCreateSuccess(array $data, TagCollection $tags): void
    {
        $post = PostFactory::create($data, $tags);

        self::assertEquals($data['id'], $post->getId());
        self::assertEquals(htmlspecialchars($data['title']), $post->getTitle());
        self::assertEquals($data['slug'], $post->getSlug());
        self::assertEquals(htmlspecialchars($data['content']), $post->getContent());
        self::assertEquals(AuthorFactory::create($data), $post->getAuthor());
        self::assertEquals(RatingFactory::create($data), $post->getRating());
        self::assertEquals($data['comments_count'], $post->getCommentsCount());
        self::assertEquals((bool)$data['published'], $post->isPublished());
        self::assertEquals($data['community_slug'] ?? '', $post->getCommunitySlug());
        self::assertEquals($data['community_name'] ?? '', $post->getCommunityName());
        self::assertEquals(new DateTime($data['created_at']), $post->getCreatedAt());
        self::assertEquals($tags, $post->getTags());

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
                "tags"             => $tags->toArray(),
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
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        PostFactory::create($data, new TagCollection());
    }

    /**
     * @dataProvider newDataProvider
     * @param string $communitySlug
     * @throws Exception
     */
    public function testPostFactoryCreateNewSuccess(string $communitySlug): void
    {
        $request = $this->createRequest();
        $tags = new TagCollection();
        $post = PostFactory::createNew($request, $tags, $communitySlug);

        self::assertTrue(Uuid::isValid($post->getId()));
        self::assertTrue(mb_strlen($post->getSlug()) > 10);
        self::assertEquals($request->getTitle(), $post->getTitle());
        self::assertEquals($this->convertToHtml($request->getContent()), $post->getHtmlContent());
        self::assertEquals(PostStatus::DEFAULT, $post->getStatus()->getId());
        self::assertEquals($request->getAuthor()->getId(), $post->getAuthor()->getId());
        self::assertEquals($request->getAuthor()->getName(), $post->getAuthor()->getName());
        self::assertEquals($request->getAuthor()->getAvatar(), $post->getAuthor()->getAvatar());
        self::assertEquals($request->getAuthor()->getLevel()->getLevel(), $post->getAuthor()->getLevel());
        self::assertEquals($request->getAuthor()->getName(), $post->getAuthor()->getName());
        self::assertEquals(0, $post->getRating()->getRating());
        self::assertEquals(0, $post->getRating()->getLikes());
        self::assertEquals(0, $post->getRating()->getDislikes());
        self::assertEquals(0, $post->getCommentsCount());
        self::assertEquals(PostInterface::DEFAULT_PUBLISHED, $post->isPublished());
        self::assertFalse($post->isLiked());
        self::assertTrue((new DateTime())->diff($post->getCreatedAt())->s <= 1);
        self::assertEquals($tags, $post->getTags());
        self::assertEquals($communitySlug, $post->getCommunitySlug());
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
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => 2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'is_liked'         => true,
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'community_slug'   => null,
                    'community_name'   => null,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                ],
                new TagCollection(),
            ],
            [
                // Без тегов и updated_at != null
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::SILVER,
                    'likes'            => 10,
                    'dislikes'         => 5,
                    'user_reaction'    => 0,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'is_liked'         => false,
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'community_slug'   => null,
                    'community_name'   => null,
                    'created_at'       => '2019-08-12 19:00:00',
                    'updated_at'       => '2019-08-15 20:20:00',
                ],
                new TagCollection(),
            ],
            [
                // С тегами и updated_at = null
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::GOLD,
                    'likes'            => 1,
                    'dislikes'         => 6,
                    'user_reaction'    => -1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'is_liked'         => true,
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'community_slug'   => null,
                    'community_name'   => null,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                ],
                new TagCollection(),
            ],
            [
                // Со спецсимволами в title и content
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => '<b>Title</b>',
                    'slug'             => 'title-slug',
                    'content'          => '<h1>Post content</h1>',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DIAMOND,
                    'likes'            => 0,
                    'dislikes'         => 0,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'is_liked'         => true,
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'community_slug'   => 'diablo-2-wiki',
                    'community_name'   => 'Diablo 2',
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                ],
                new TagCollection(),
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
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
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
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_ID,
            ],
            [
                // id некорректного типа
                [
                    'id'               => 10,
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
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
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_ID,
            ],

            // title
            [
                // отсутствует title
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
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
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_TITLE,
            ],
            [
                // title некорректного типа
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => ['Title'],
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
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
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_TITLE,
            ],
            [
                // title короче минимальной длины
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => self::generateString(PostInterface::TITLE_MIN_LENGTH - 1),
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
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
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_TITLE_LENGTH . PostInterface::TITLE_MIN_LENGTH . '-' . PostInterface::TITLE_MAX_LENGTH,
            ],
            [
                // title длиннее максимальной длины
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => self::generateString(PostInterface::TITLE_MAX_LENGTH + 1),
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
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
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_TITLE_LENGTH . PostInterface::TITLE_MIN_LENGTH . '-' . PostInterface::TITLE_MAX_LENGTH,
            ],

            // slug
            [
                // отсутствует slug
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
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
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_SLUG,
            ],
            [
                // slug некорректного типа
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 10.4,
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
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
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_SLUG,
            ],
            [
                // slug over min length
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => self::generateString(PostInterface::SLUG_MIN_LENGTH - 1),
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
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
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_SLUG_LENGTH . PostInterface::SLUG_MIN_LENGTH . '-' . PostInterface::SLUG_MAX_LENGTH,
            ],
            [
                // slug over max length
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => self::generateString(PostInterface::SLUG_MAX_LENGTH + 1),
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
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
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_SLUG_LENGTH . PostInterface::SLUG_MIN_LENGTH . '-' . PostInterface::SLUG_MAX_LENGTH,
            ],

            // content
            [
                // отсутствует content
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'status_id'        => PostStatusInterface::DEFAULT,
                    'html_content'     => '<p>Post content</p>',
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
                    'community_slug'   => null,
                    'community_name'   => null,
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
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
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
                    'community_slug'   => null,
                    'community_name'   => null,
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
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
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
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_CONTENT_LENGTH . PostInterface::CONTENT_MIN_LENGTH . '-' . PostInterface::CONTENT_MAX_LENGTH,
            ],
            [
                // content длиннее максимальной длины
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => self::generateString(PostInterface::CONTENT_MAX_LENGTH + 1),
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
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
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_CONTENT_LENGTH . PostInterface::CONTENT_MIN_LENGTH . '-' . PostInterface::CONTENT_MAX_LENGTH,
            ],

            // html_content
            [
                // отсутствует html_content
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'status_id'        => PostStatusInterface::DEFAULT,
                    'content'          => '[p]Post content[/p]',
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
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_HTML_CONTENT,
            ],
            [
                // html_content некорректного типа
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => ['<p>Post content</p>'],
                    'status_id'        => PostStatusInterface::DEFAULT,
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
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_HTML_CONTENT,
            ],
            [
                // html_content короче минимальной длины
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => self::generateString(PostInterface::HTML_CONTENT_MIN_LENGTH - 1),
                    'status_id'        => PostStatusInterface::DEFAULT,
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
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_HTML_CONTENT_LENGTH . PostInterface::HTML_CONTENT_MIN_LENGTH . '-' . PostInterface::HTML_CONTENT_MAX_LENGTH,
            ],
            [
                // html_content длиннее максимальной длины
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => self::generateString(PostInterface::HTML_CONTENT_MAX_LENGTH  + 1),
                    'status_id'        => PostStatusInterface::DEFAULT,
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
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_HTML_CONTENT_LENGTH . PostInterface::HTML_CONTENT_MIN_LENGTH . '-' . PostInterface::HTML_CONTENT_MAX_LENGTH,
            ],

            [
                // отсутствует status
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
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
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_STATUS_ID,
            ],
            [
                // status некорректного типа
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => '1',
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
                    'community_slug'   => null,
                    'community_name'   => null,
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
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_COMMENTS_COUNT,
            ],
            [
                // comments_count некорректного типа
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => '3',
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                    'community_slug'   => null,
                    'community_name'   => null,
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
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_PUBLISHED,
            ],
            [
                // published некорректного типа
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => true, // не смотря на тип bool у объекта, из базы ожидается получить int
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                    'community_slug'   => null,
                    'community_name'   => null,
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
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'updated_at'       => null,
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_CREATED_AT,
            ],
            [
                // created_at некорректного типа
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => [],
                    'updated_at'       => null,
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_CREATED_AT,
            ],
            [
                // created_at некорректного значения даты
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '9999-99-99 99:99:99',
                    'updated_at'       => null,
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                    'community_slug'   => null,
                    'community_name'   => null,
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
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_UPDATED_AT,
            ],
            [
                // updated_at некорректного типа
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => 'null',
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_UPDATED_AT,
            ],
            [
                // updated_at некорректного значения даты
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => -2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => '9999-99-99 99:99:99',
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'is_liked'         => true,
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_UPDATED_AT,
            ],
            [
                // отсутствует is_liked
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
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
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_IS_LIKED,
            ],
            [
                // is_liked некорректного типа
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
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
                    'is_liked'         => 123,
                    'community_slug'   => null,
                    'community_name'   => null,
                ],
                PostException::INVALID_IS_LIKED,
            ],
            // miss community_slug
            [
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => 2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'is_liked'         => true,
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'community_name'   => null,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                ],
                PostException::INVALID_COMMUNITY_SLUG,
            ],
            // community_slug invalid type
            [
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => 2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'is_liked'         => true,
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'community_slug'   => 1,
                    'community_name'   => null,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                ],
                PostException::INVALID_COMMUNITY_SLUG,
            ],
            // miss community_name
            [
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => 2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'is_liked'         => true,
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'community_slug'   => null,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                ],
                PostException::INVALID_COMMUNITY_NAME,
            ],
            // community_name invalid type
            [
                [
                    'id'               => 'b5d82b2c-6be2-42a0-85c6-821a170c68eb',
                    'title'            => 'Title',
                    'slug'             => 'title-slug',
                    'content'          => '[p]Post content[/p]',
                    'html_content'     => '<p>Post content</p>',
                    'status_id'        => PostStatusInterface::DEFAULT,
                    'likes'            => 12,
                    'dislikes'         => 2,
                    'user_reaction'    => 1,
                    'comments_count'   => 3,
                    'published'        => 1,
                    'is_liked'         => true,
                    'author_id'        => '67ea6431-4523-42ee-bfa0-e302d6447acb',
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'community_slug'   => null,
                    'community_name'   => true,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                ],
                PostException::INVALID_COMMUNITY_NAME,
            ],

            // Проверка валидации параметров автора сделана в AuthorFactoryTest
        ];
    }

    /**
     * @return array
     */
    public function newDataProvider(): array
    {
        return [
            [
                'default',
            ],
            [
                'diablo-2-wiki',
            ],
        ];
    }

    /**
     * @return CreatePostRequest
     * @throws AppException
     */
    private function createRequest(): CreatePostRequest
    {
        return new CreatePostRequest(
            'title',
            '[p]content[p][line][h2]Video:[/h2][video]5j4g_K55Lcw[/video][br][img]images/upload/image.png[img]',
            ['tag-1', 'tag-2', 'tag-3'],
            $this->createUser(),
        );
    }

    /**
     * @param string $content
     * @return string
     */
    private function convertToHtml(string $content): string
    {
        $text = htmlspecialchars($content);

        $text = preg_replace( '[\[img\]([^"\s]+?)\[/img\]]m', PostInterface::IMAGE_TEMPLATE, $text);

        return str_replace(
            ['[p]', '[/p]', '[video]', '[/video]', '[line]', '[h2]', '[/h2]', '[br]'],
            ['<p>', '</p>', PostInterface::VIDEO_PREFIX, PostInterface::VIDEO_SUFFIX, PostInterface::LINE, '<h2>', '</h2>', '<br />'],
            $text
        );
    }
}
