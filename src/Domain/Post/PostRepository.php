<?php

declare(strict_types=1);

namespace App\Domain\Post;

use App\Domain\Auth\AuthInterface;
use App\Domain\Post\Tag\TagRepository;
use Ramsey\Uuid\Uuid;
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
     * @param AuthInterface|null $user
     * @return PostInterface|null
     * @throws AppException
     */
    public function get(string $slug, ?AuthInterface $user = null): ?PostInterface
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
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

        if ($user === null) {
            $data['user_reaction'] = 0;
            $data['is_liked'] = true;
        } elseif ($user->getId() === $data['author_id']) {
            $data['user_reaction'] = 0;
            $data['is_liked'] = false;
        } else {
            $data['user_reaction'] = $this->getUserReaction($slug, $user);
            $data['is_liked'] = $data['user_reaction'] === 0;
        }

        return PostFactory::create($data, $this->tagRepository->getByPostId($data['id'] ?? ''));
    }

    /**
     * @param string $slug
     * @param string $accountId
     * @return bool
     * @throws AppException
     */
    public function isOwner(string $slug, string $accountId): bool
    {
        return (bool)$this->container->getConnectionPool()->getConnection()->query(
            'SELECT `id` FROM `posts` WHERE `author_id` = ? AND `slug` = ?',
            [
                ['type' => 's', 'value' => $accountId],
                ['type' => 's', 'value' => $slug],
            ],
            true
        );
    }

    /**
     * @param string $slug
     * @param string $accountId
     * @return bool
     * @throws AppException
     */
    public function existLiked(string $slug, string $accountId): bool
    {
        return (bool)$this->container->getConnectionPool()->getConnection()->query(
                'SELECT `id` FROM `lk_account_like_post` WHERE `account_id` = ? AND `post_slug` = ?',
                [
                    ['type' => 's', 'value' => $accountId],
                    ['type' => 's', 'value' => $slug],
                ],
                true
            );
    }

    /**
     * @param string $slug
     * @param string $accountId
     * @param int $value
     * @throws AppException
     */
    public function like(string $slug, string $accountId, int $value): void
    {
        $this->changeRating($slug, $accountId, $value);
    }

    /**
     * @param string $slug
     * @param string $accountId
     * @param int $value
     * @throws AppException
     */
    public function dislike(string $slug, string $accountId, int $value): void
    {
        $this->changeRating($slug, $accountId, $value, false);
    }

    /**
     * @param string $slug
     * @param string $accountId
     * @param int $value
     * @param bool $like
     * @throws AppException
     */
    protected function changeRating(string $slug, string $accountId, int $value, bool $like = true): void
    {
        $connection = $this->container->getConnectionPool()->getConnection();

        $connection->query(
            'INSERT INTO `lk_account_like_post` (`id`, `account_id`, `post_slug`, `value`) VALUES (?, ?, ?, ?)',
            [
                ['type' => 's', 'value' => Uuid::uuid4()],
                ['type' => 's', 'value' => $accountId],
                ['type' => 's', 'value' => $slug],
                ['type' => 'i', 'value' => $like ? $value : -$value],
            ],
        );

        if ($like) {
            $connection->query(
                'UPDATE `posts` SET `likes` = `likes` + ? WHERE `slug` = ?',
                [
                    ['type' => 'i', 'value' => $value],
                    ['type' => 's', 'value' => $slug],
                ],
            );
        } else {
            $connection->query(
                'UPDATE `posts` SET `dislikes` = `dislikes` + ? WHERE `slug` = ?',
                [
                    ['type' => 'i', 'value' => $value],
                    ['type' => 's', 'value' => $slug],
                ],
            );
        }
    }

    /**
     * @param string $slug
     * @param AuthInterface $user
     * @return int
     * @throws AppException
     */
    private function getUserReaction(string $slug, AuthInterface $user): int
    {
        return $this->container->getConnectionPool()->getConnection()->query(
            'SELECT `value` FROM `lk_account_like_post` WHERE `account_id` = ? AND `post_slug` = ?',
            [
                ['type' => 's', 'value' => $user->getId()],
                ['type' => 's', 'value' => $slug],
            ],
            true
        )['value'] ?? 0;
    }
}
