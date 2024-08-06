<?php

declare(strict_types=1);

namespace App\Domain\Post;

use App\Domain\Post\Tag\TagRepository;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;

class PostRepository
{
    private Container $container;
    private TagRepository $tagRepository;

    public function __construct(Container $container, ?TagRepository $tagRepository = null)
    {
        $this->container = $container;
        $this->tagRepository = $tagRepository ?? new TagRepository($container);
    }

    /**
     * @param string $slug
     * @return PostInterface|null
     * @throws AppException
     */
    public function get(string $slug): ?PostInterface
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
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

        if (!$data) {
            return null;
        }

        // TODO Mock
        $data['user_reaction'] = 0;
        $data['is_liked'] = false;

        return PostFactory::create($data, $this->tagRepository->getByPostId($data['id'] ?? ''));
    }
}
