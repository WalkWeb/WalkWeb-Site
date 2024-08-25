<?php

declare(strict_types=1);

namespace App\Domain\Comment;

use App\Domain\Auth\AuthInterface;
use Ramsey\Uuid\Uuid;
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
        $data['is_liked'] = false;

        return CommentFactory::create($data);
    }

    /**
     * @param string $id
     * @return string|null
     * @throws AppException
     */
    public function getAuthorId(string $id): ?string
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT `author_id` FROM `post_comments` WHERE `id` = ?',
            [['type' => 's', 'value' => $id]],
            true
        );

        if (!$data || !array_key_exists('author_id', $data) || (!is_string($data['author_id']) && !is_null($data['author_id']))) {
            throw new AppException(CommentException::GET_AUTHOR_ERROR);
        }

        return $data['author_id'];
    }

    /**
     * @param string $postId
     * @param AuthInterface|null $user
     * @return CommentCollection
     * @throws AppException
     */
    public function getByPost(string $postId, ?AuthInterface $user = null): CommentCollection
    {
        if ($user === null) {
            return $this->getCollectionNoLikes($postId);
        }

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
            `lk`.`value` as `user_reaction`,
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
            LEFT JOIN `lk_account_like_comment` lk ON `post_comments`.`id` = `lk`.`comment_id` AND `lk`.`account_id` = ?
                                 
            WHERE `post_comments`.`post_id` = ?
            
            ORDER BY `post_comments`.`created_at`',
            [
                ['type' => 's', 'value' => $user->getId()],
                ['type' => 's', 'value' => $postId],
            ],
        );

        foreach ($data as &$datum) {
            $datum['user_reaction'] = $datum['user_reaction'] ?? 0;
            $isLiked = true;

            if ($user->getId() === $datum['author_id']) {
                $isLiked = false;
            }

            if ($datum['user_reaction'] !== 0) {
                $isLiked = false;
            }

            $datum['is_liked'] = $isLiked;
        }

        return CommentCollectionFactory::create($data);
    }

    /**
     * @param CommentInterface $comment
     * @throws AppException
     */
    public function add(CommentInterface $comment): void
    {
        $this->container->getConnectionPool()->getConnection()->query(
            'INSERT INTO `post_comments` (`id`, `post_id`, `author_id`, `message`, `approved`, `parent_id`, `level`, 
                 `likes`, `dislikes`, `created_at`, `updated_at`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                ['type' => 's', 'value' => $comment->getId()],
                ['type' => 's', 'value' => $comment->getPostId()],
                ['type' => 's', 'value' => $comment->getAuthorId()],
                ['type' => 's', 'value' => $comment->getMessage()],
                ['type' => 'i', 'value' => (int)$comment->isApproved()],
                ['type' => 's', 'value' => $comment->getParentId()],
                ['type' => 'i', 'value' => $comment->getLevel()],
                ['type' => 'i', 'value' => $comment->getRating()->getLikes()],
                ['type' => 'i', 'value' => $comment->getRating()->getDislikes()],
                ['type' => 's', 'value' => $comment->getCreatedAt()->format('Y-m-d H:i:s')],
                ['type' => 's', 'value' => $comment->getUpdatedAt()->format('Y-m-d H:i:s')],
            ]
        );
    }

    /**
     * @param string $commentId
     * @param string $accountId
     * @param int $value
     * @throws AppException
     */
    public function like(string $commentId, string $accountId, int $value): void
    {
        $this->changeRating($commentId, $accountId, $value);
    }

    /**
     * @param string $commentId
     * @param string $accountId
     * @param int $value
     * @throws AppException
     */
    public function dislike(string $commentId, string $accountId, int $value): void
    {
        $this->changeRating($commentId, $accountId, $value, false);
    }

    /**
     * @param string $id
     * @param string $accountId
     * @return bool
     * @throws AppException
     */
    public function isOwner(string $id, string $accountId): bool
    {
        return (bool)$this->container->getConnectionPool()->getConnection()->query(
            'SELECT `id` FROM `post_comments` WHERE `author_id` = ? AND `id` = ?',
            [
                ['type' => 's', 'value' => $accountId],
                ['type' => 's', 'value' => $id],
            ],
            true
        );
    }

    /**
     * @param string $id
     * @param string $accountId
     * @return bool
     * @throws AppException
     */
    public function existLiked(string $id, string $accountId): bool
    {
        return (bool)$this->container->getConnectionPool()->getConnection()->query(
            'SELECT `id` FROM `lk_account_like_comment` WHERE `account_id` = ? AND `comment_id` = ?',
            [
                ['type' => 's', 'value' => $accountId],
                ['type' => 's', 'value' => $id],
            ],
            true
        );
    }

    /**
     * @param string $id
     * @param string $accountId
     * @param int $value
     * @param bool $like
     * @throws AppException
     */
    private function changeRating(string $id, string $accountId, int $value, bool $like = true): void
    {
        $connection = $this->container->getConnectionPool()->getConnection();

        $connection->query(
            'INSERT INTO `lk_account_like_comment` (`id`, `account_id`, `comment_id`, `value`) VALUES (?, ?, ?, ?)',
            [
                ['type' => 's', 'value' => Uuid::uuid4()->toString()],
                ['type' => 's', 'value' => $accountId],
                ['type' => 's', 'value' => $id],
                ['type' => 'i', 'value' => $like ? $value : -$value],
            ],
        );

        if ($like) {
            $connection->query(
                'UPDATE `post_comments` SET `likes` = `likes` + ? WHERE `id` = ?',
                [
                    ['type' => 'i', 'value' => $value],
                    ['type' => 's', 'value' => $id],
                ],
            );
        } else {
            $connection->query(
                'UPDATE `post_comments` SET `dislikes` = `dislikes` + ? WHERE `id` = ?',
                [
                    ['type' => 'i', 'value' => $value],
                    ['type' => 's', 'value' => $id],
                ],
            );
        }
    }

    /**
     * @param string $postId
     * @return CommentCollection
     * @throws AppException
     */
    private function getCollectionNoLikes(string $postId): CommentCollection
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
            
            ORDER BY `post_comments`.`created_at`',
            [['type' => 's', 'value' => $postId]],
        );

        foreach ($data as &$datum) {
            $datum['is_liked'] = false;
            $datum['user_reaction'] = 0;
        }

        return CommentCollectionFactory::create($data);
    }
}
