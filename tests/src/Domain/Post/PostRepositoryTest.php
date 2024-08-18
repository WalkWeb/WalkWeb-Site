<?php

declare(strict_types=1);

namespace Test\src\Domain\Post;

use App\Domain\Post\PostFactory;
use App\Domain\Post\PostInterface;
use App\Domain\Post\PostRepository;
use App\Domain\Post\Status\PostStatusInterface;
use App\Domain\Post\Tag\TagCollection;
use App\Domain\Post\Tag\TagRepository;
use DateTime;
use Exception;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class PostRepositoryTest extends AbstractTest
{
    /**
     * @dataProvider getSuccessDataProvider
     * @param string $slug
     * @throws Exception
     */
    public function testPostRepositoryGetSuccess(string $slug): void
    {
        $post = $this->getRepository()->get($slug);

        $data = $this->getData($slug);

        self::assertEquals($data['id'], $post->getId());
        self::assertEquals($data['title'], $post->getTitle());
        self::assertEquals($slug, $post->getSlug());
        self::assertEquals($data['content'], $post->getContent());
        self::assertEquals($data['status_id'], $post->getStatus()->getId());
        self::assertEquals($data['likes'], $post->getRating()->getLikes());
        self::assertEquals($data['dislikes'], $post->getRating()->getDislikes());
        self::assertEquals($data['likes'] - $data['dislikes'], $post->getRating()->getRating());
        self::assertEquals($data['comments_count'], $post->getCommentsCount());
        self::assertEquals($data['published'], $post->isPublished());
        self::assertEquals($data['author_id'], $post->getAuthor()->getId());
        self::assertEquals($data['author_name'], $post->getAuthor()->getName());
        self::assertEquals($data['author_level'], $post->getAuthor()->getLevel());
        self::assertEquals($data['author_avatar'], $post->getAuthor()->getAvatar());
        self::assertEquals($data['author_status_id'], $post->getAuthor()->getStatus()->getId());
        self::assertEquals(new DateTime($data['created_at']), $post->getCreatedAt());
        self::assertEquals(new DateTime($data['updated_at']), $post->getUpdatedAt());

        self::assertEquals($this->getTagRepository()->getByPostId($post->getId()), $post->getTags());
    }

    /**
     * @throws AppException
     */
    public function testPostRepositoryGetNotFound(): void
    {
        self::assertNull($this->getRepository()->get('3240e7a7-9c6a-4a4e-99ec-90edcd400379'));
    }

    /**
     * @dataProvider addDataProvider
     * @param PostInterface $post
     * @throws AppException
     */
    public function testPostRepositoryAdd(PostInterface $post): void
    {
        $this->getRepository()->add($post);

        $data = $this->getData($post->getSlug());

        self::assertEquals($post->getId(), $data['id']);
        self::assertEquals($post->getAuthor()->getId(), $data['author_id']);
        self::assertEquals($post->getTitle(), $data['title']);
        self::assertEquals($post->getSlug(), $data['slug']);
        self::assertEquals($post->getContent(), $data['content']);
        self::assertEquals($post->getHtmlContent(), $data['html_content']);
        self::assertEquals($post->getStatus()->getId(), $data['status_id']);
        self::assertEquals($post->getRating()->getLikes(), $data['likes']);
        self::assertEquals($post->getRating()->getDislikes(), $data['dislikes']);
        self::assertEquals($post->getCommentsCount(), $data['comments_count']);
        self::assertEquals($post->isPublished(), $data['published']);

        // TODO
        self::assertEquals(1, $data['approved']);
        self::assertEquals(0, $data['moderated']);
    }

    /**
     * @throws AppException
     */
    public function testPostRepositoryGetAll(): void
    {
        $user = $this->getUser('VBajfT8P6PFtrkHhCqb7ZNwIFG45a4');
        $posts = $this->getRepository()->getAll(0, 20, $user);

        self::assertCount(11, $posts);
    }

    /**
     * @throws AppException
     */
    public function testPostRepositoryGetIdBySlugSuccess(): void
    {
        self::assertEquals('7684ad22-613b-4c65-9bad-b7dfdd394c01', $this->getRepository()->getIdBySlug('slug-post-1-1000'));
    }

    /**
     * @throws AppException
     */
    public function testPostRepositoryGetIdBySlugNotFound(): void
    {
        self::assertNull($this->getRepository()->getIdBySlug('slug-123-1-12312'));
    }

    /**
     * @throws AppException
     */
    public function testPostRepositoryGetByTagAuth(): void
    {
        $user = $this->getUser('VBajfT8P6PFtrkHhCqb7ZNwIFG45a4');
        $posts = $this->getRepository()->getPostByTag('path-of-exile', 0, 20, $user);

        self::assertCount(1, $posts);
    }

    /**
     * @throws AppException
     */
    public function testPostRepositoryGetByTagNoAuth(): void
    {
        $posts = $this->getRepository()->getPostByTag('path-of-exile', 0, 20);

        self::assertCount(1, $posts);
    }

    /**
     * @return array
     */
    public function getSuccessDataProvider(): array
    {
        return [
            [
                'slug-post-1-1000',
            ],
            [
                'slug-post-2-1000',
            ],
            [
                'slug-post-3-1000',
            ],
        ];
    }

    /**
     * @return array
     * @throws AppException
     */
    public function addDataProvider(): array
    {
        return [
            [
                PostFactory::create([
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
                    'author_id'        => self::DEMO_MODERATOR,
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'created_at'       => '2019-08-12 19:05:19',
                    'updated_at'       => null,
                ], new TagCollection()),
            ],
            [
                PostFactory::create([
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
                    'author_id'        => self::DEMO_CHAT_ADMIN,
                    'author_name'      => 'Name',
                    'author_avatar'    => 'avatar.png',
                    'author_level'     => 25,
                    'author_status_id' => 1,
                    'created_at'       => '2019-08-12 19:00:00',
                    'updated_at'       => '2019-08-15 20:20:00',
                ], new TagCollection()),
            ],
        ];
    }

    /**
     * @return PostRepository
     * @throws AppException
     */
    private function getRepository(): PostRepository
    {
        return new PostRepository(self::getContainer());
    }

    /**
     * @return TagRepository
     * @throws AppException
     */
    private function getTagRepository(): TagRepository
    {
        return new TagRepository(self::getContainer());
    }

    /**
     * @param string $slug
     * @return array
     * @throws AppException
     */
    private function getData(string $slug): array
    {
        return self::getContainer()->getConnectionPool()->getConnection()->query(
            'SELECT 
       
            `posts`.`id`,
            `posts`.`title`,
            `posts`.`slug`,
            `posts`.`content`,
            `posts`.`html_content`,
            `posts`.`status_id`,
            `posts`.`likes`,
            `posts`.`dislikes`,
            `posts`.`comments_count`,
            `posts`.`published`,
            `posts`.`approved`,
            `posts`.`moderated`,
            `posts`.`created_at`,
            `posts`.`updated_at`,
            
            `accounts`.`id` as `author_id`,
            `accounts`.`name` as `author_name`,
            `accounts`.`status_id` as `author_status_id`,
       
            `characters_main`.`level` as `author_level`,
       
            `avatars`.`small_url` as `author_avatar`

            FROM `posts` 

            JOIN `accounts` on `posts`.`author_id` = `accounts`.`id`
            JOIN `characters_main` on `accounts`.`id` = `characters_main`.`account_id`
            JOIN `characters` on `accounts`.`character_id` = `characters`.`id`
            JOIN `avatars` on `characters`.`avatar_id` = `avatars`.`id`

            WHERE `posts`.`slug` = ?',
            [['type' => 's', 'value' => $slug]],
            true
        );
    }
}
