<?php

declare(strict_types=1);

namespace App\Domain\Community;

use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;

class CommunityRepository
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $slug
     * @return CommunityInterface|null
     * @throws AppException
     */
    public function get(string $slug): ?CommunityInterface
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT 
       
                `id`, `level`, `name`, `slug`, `description`, `icon`, `icon_small`, `head_image`, `followers`, 
                `fixed_post_id`, `menu`, `owner_id`, `total_post_count`, `silver_post_count`, `gold_post_count`, 
                `diamond_post_count`, `total_comment_count`, `created_at`, `updated_at` 

                FROM `communities` 

                WHERE `slug` = ?',
                [['type' => 's', 'value' => $slug]],
            true
        );

        if (!$data) {
            return null;
        }

        return CommunityFactory::create($data);
    }

    /**
     * @return CommunityCollection
     * @throws AppException
     */
    public function getAll(): CommunityCollection
    {
        return CommunityCollectionFactory::create($this->container->getConnectionPool()->getConnection()->query(
            'SELECT 
       
                `id`, `level`, `name`, `slug`, `description`, `icon`, `icon_small`, `head_image`, `followers`, 
                `fixed_post_id`, `menu`, `owner_id`, `total_post_count`, `silver_post_count`, `gold_post_count`, 
                `diamond_post_count`, `total_comment_count`, `created_at`, `updated_at` 

                FROM `communities` 

                ORDER BY `followers` DESC',
        ));
    }
}
