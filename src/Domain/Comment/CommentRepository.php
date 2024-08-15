<?php

declare(strict_types=1);

namespace App\Domain\Comment;

use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;

class CommentRepository
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $id
     * @return CommentInterface|null
     * @throws AppException
     */
    public function get(string $id): ?CommentInterface
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT 

            `post_comments`.`id`,
            `post_comments`.`post_id`,
            `post_comments`.`author_id`,
            `post_comments`.`guest_name`,
            `post_comments`.`message`,
            `post_comments`.`approved`,
            `post_comments`.`parent_id`,
            `post_comments`.`level`,
            `post_comments`.`likes`,
            `post_comments`.`dislikes`,
            `post_comments`.`created_at`,
            `post_comments`.`updated_at`,

            `accounts`.`name` as `author_name`,
            `avatars`.`origin_url` as `author_avatar`,
            `characters_main`.`level` as `author_level`
       
            FROM `post_comments` 

            LEFT JOIN `accounts` ON `accounts`.`id` = `post_comments`.`author_id`
            LEFT JOIN `characters_main` ON `accounts`.`main_character_id` = `characters_main`.`id`
            LEFT JOIN `characters` ON `accounts`.`character_id` = `characters`.`id`
            LEFT JOIN `avatars` ON `characters`.`avatar_id` = `avatars`.`id`
                                 
            WHERE `post_comments`.`id` = ?',
            [['type' => 's', 'value' => $id]],
            true
        );

        if (!$data) {
            return null;
        }

        // TODO Mock
        $data['user_reaction'] = 0;

        return CommentFactory::create($data);
    }

    /**
     * @param string $postId
     * @return CommentCollection
     * @throws AppException
     */
    public function getByPost(string $postId): CommentCollection
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT 

            `post_comments`.`id`,
            `post_comments`.`post_id`,
            `post_comments`.`author_id`,
            `post_comments`.`guest_name`,
            `post_comments`.`message`,
            `post_comments`.`approved`,
            `post_comments`.`parent_id`,
            `post_comments`.`level`,
            `post_comments`.`likes`,
            `post_comments`.`dislikes`,
            `post_comments`.`created_at`,
            `post_comments`.`updated_at`,

            `accounts`.`name` as `author_name`,
            `avatars`.`origin_url` as `author_avatar`,
            `characters_main`.`level` as `author_level`
       
            FROM `post_comments` 

            LEFT JOIN `accounts` ON `accounts`.`id` = `post_comments`.`author_id`
            LEFT JOIN `characters_main` ON `accounts`.`main_character_id` = `characters_main`.`id`
            LEFT JOIN `characters` ON `accounts`.`character_id` = `characters`.`id`
            LEFT JOIN `avatars` ON `characters`.`avatar_id` = `avatars`.`id`
                                 
            WHERE `post_comments`.`post_id` = ?
            
            ORDER BY `post_comments`.`created_at` DESC',
            [['type' => 's', 'value' => $postId]],
        );

        foreach ($data as &$datum) {
            // TODO Mock
            $datum['user_reaction'] = 0;
        }

        return CommentCollectionFactory::create($data);
    }
}
