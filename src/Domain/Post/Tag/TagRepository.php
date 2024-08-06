<?php

declare(strict_types=1);

namespace App\Domain\Post\Tag;

use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;

class TagRepository
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $postId
     * @return TagCollection
     * @throws AppException
     */
    public function getByPostId(string $postId): TagCollection
    {
        return TagCollectionFactory::create(
            $this->container->getConnectionPool()->getConnection()->query(
                'SELECT 

                `post_tags`.`id`,
                `post_tags`.`name`,
                `post_tags`.`slug`,
                `post_tags`.`icon`,
                `post_tags`.`preview_post_id`,
                `post_tags`.`approved`,
                `post_tags`.`created_at`
                
                FROM `lk_post_tag`

                JOIN `post_tags` on `lk_post_tag`.`tag_id` = `post_tags`.`id`

                WHERE `lk_post_tag`.`post_id` = ?',
                [['type' => 's', 'value' => $postId]],
            )
        );
    }
}
