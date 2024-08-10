<?php

declare(strict_types=1);

namespace App\Domain\Post\Tag;

use App\Domain\Post\DTO\CreatePostRequest;
use Exception;
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
     * @param string $name
     * @return TagInterface|null
     * @throws AppException
     */
    public function getByName(string $name): ?TagInterface
    {
        $data =             $this->container->getConnectionPool()->getConnection()->query(
            'SELECT `id`, `name`, `slug`, `icon`, `preview_post_id`, `approved` FROM `post_tags` WHERE `name` = ?',
            [['type' => 's', 'value' => mb_strtolower($name)]],
            true
        );

        if (!$data) {
            return null;
        }

        return TagFactory::create($data);
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

    /**
     * @param TagInterface $tag
     * @throws AppException
     */
    public function add(TagInterface $tag): void
    {
        $this->container->getConnectionPool()->getConnection()->query(
            'INSERT INTO `post_tags` (`id`, `name`, `slug`, `icon`, `preview_post_id`, `approved`) VALUES (?, ?, ?, ?, ?, ?)',
            [
                ['type' => 's', 'value' => $tag->getId()],
                ['type' => 's', 'value' => mb_strtolower($tag->getName())],
                ['type' => 's', 'value' => mb_strtolower($tag->getSlug())],
                ['type' => 's', 'value' => $tag->getIcon()],
                ['type' => 's', 'value' => $tag->getPreviewPostId()],
                ['type' => 'i', 'value' => (int)$tag->isApproved()],
            ],
        );
    }

    /**
     * @param CreatePostRequest $request
     * @return TagCollection
     * @throws AppException
     * @throws Exception
     */
    public function saveCollection(CreatePostRequest $request): TagCollection
    {
        $tags = new TagCollection();

        foreach ($request->getTags() as $tagName) {
            if ($tag = $this->getByName($tagName)) {
                $tags->add($tag);
            } else {
                $tag = TagFactory::createNew($tagName);
                $this->add($tag);
                $tags->add($tag);
            }
        }

        return $tags;
    }
}
