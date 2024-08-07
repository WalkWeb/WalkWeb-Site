<?php

declare(strict_types=1);

namespace Test\src\Domain\Post;

use App\Domain\Post\PostRepository;
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
            `posts`.`status_id`,
            `posts`.`likes`,
            `posts`.`dislikes`,
            `posts`.`comments_count`,
            `posts`.`published`,
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
