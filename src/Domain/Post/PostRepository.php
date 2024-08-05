<?php

declare(strict_types=1);

namespace App\Domain\Post;

use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;

class PostRepository
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $id
     * @return PostInterface|null
     * @throws AppException
     */
    public function get(string $id): ?PostInterface
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

            WHERE `posts`.`id` = ?',
            [['type' => 's', 'value' => $id]],
            true
        );

        if (!$data) {
            return null;
        }

        // TODO Mock
        $data['user_reaction'] = 0;
        $data['tags'] = [];
        $data['is_liked'] = false;

        return PostFactory::create($data);
    }
}
