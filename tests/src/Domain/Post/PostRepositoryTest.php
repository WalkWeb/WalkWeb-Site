<?php

declare(strict_types=1);

namespace Test\src\Domain\Post;

use App\Domain\Post\PostRepository;
use DateTime;
use Exception;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class PostRepositoryTest extends AbstractTest
{
    /**
     * @dataProvider getSuccessDataProvider
     * @param string $id
     * @throws Exception
     */
    public function testPostRepositoryGetSuccess(string $id): void
    {
        $post = $this->getRepository()->get($id);

        $data = $this->getData($id);

        self::assertEquals($id, $post->getId());
        self::assertEquals($data['title'], $post->getTitle());
        self::assertEquals($data['slug'], $post->getSlug());
        self::assertEquals($data['content'], $post->getContent());
        self::assertEquals($data['status_id'], $post->getStatus()->getId());
        self::assertEquals($data['likes'], $post->getRating()->getLikes());
        self::assertEquals($data['dislikes'], $post->getRating()->getDislikes());
        self::assertEquals($data['likes'] + $data['dislikes'], $post->getRating()->getRating());
        self::assertEquals($data['comments_count'], $post->getCommentsCount());
        self::assertEquals($data['published'], $post->isPublished());
        self::assertEquals($data['author_id'], $post->getAuthor()->getId());
        self::assertEquals($data['author_name'], $post->getAuthor()->getName());
        self::assertEquals($data['author_level'], $post->getAuthor()->getLevel());
        self::assertEquals($data['author_avatar'], $post->getAuthor()->getAvatar());
        self::assertEquals($data['status_id'], $post->getAuthor()->getStatus()->getId());
        self::assertEquals(new DateTime($data['created_at']), $post->getCreatedAt());
        self::assertEquals(new DateTime($data['updated_at']), $post->getUpdatedAt());
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
                '7684ad22-613b-4c65-9bad-b7dfdd394c01',
            ],
            [
                '7684ad22-613b-4c65-9bad-b7dfdd394c02',
            ],
        ];
    }

    /**
     * @return PostRepository
     * @throws AppException
     */
    protected function getRepository(): PostRepository
    {
        return new PostRepository(self::getContainer());
    }

    /**
     * @param string $id
     * @return array
     * @throws AppException
     */
    private function getData(string $id): array
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

            WHERE `posts`.`id` = ?',
            [['type' => 's', 'value' => $id]],
            true
        );
    }
}
