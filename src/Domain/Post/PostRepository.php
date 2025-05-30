<?php

declare(strict_types=1);

namespace App\Domain\Post;

use App\Domain\Auth\AuthInterface;
use App\Domain\Community\CommunityException;
use App\Domain\Community\CommunityRepository;
use App\Domain\Post\Collection\PostCollection;
use App\Domain\Post\Collection\PostCollectionFactory;
use App\Domain\Post\Tag\TagRepository;
use Ramsey\Uuid\Uuid;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;

class PostRepository
{
    private Container $container;
    private TagRepository $tagRepository;
    private CommunityRepository $communityRepository;

    public function __construct(
        Container $container,
        ?TagRepository $tagRepository = null,
        ?CommunityRepository $communityRepository = null
    ) {
        $this->container = $container;
        $this->tagRepository = $tagRepository ?? new TagRepository($container);
        $this->communityRepository = $communityRepository ?? new CommunityRepository($this->container);
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
       
            `avatars`.`small_url` as `author_avatar`,
       
            `communities`.`slug` as `community_slug`,
            `communities`.`name` as `community_name`

            FROM `posts` 

            JOIN `accounts` on `posts`.`author_id` = `accounts`.`id`
            JOIN `characters_main` on `accounts`.`id` = `characters_main`.`account_id`
            JOIN `characters` on `accounts`.`character_id` = `characters`.`id`
            JOIN `avatars` on `characters`.`avatar_id` = `avatars`.`id`
            LEFT JOIN `communities` on `posts`.`community_id` = `communities`.`id`    

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
     * @return string|null
     * @throws AppException
     */
    public function getAuthorId(string $slug): ?string
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT `author_id` FROM `posts` WHERE `slug` = ?',
            [['type' => 's', 'value' => $slug]],
            true
        );

        if (!$data || !array_key_exists('author_id', $data) || !is_string($data['author_id'])) {
            throw new AppException(PostException::GET_AUTHOR_ERROR);
        }

        return $data['author_id'];
    }

    /**
     * @param int $offset
     * @param int $limit
     * @param AuthInterface|null $user
     * @param string|null $communitySlug
     * @return PostCollection
     * @throws AppException
     */
    public function getAll(int $offset, int $limit, ?AuthInterface $user = null, ?string $communitySlug = null): PostCollection
    {
        if ($user === null) {
            return $this->getAllNoLikes($offset, $limit, $communitySlug);
        }

        $params = [
            ['type' => 's', 'value' => $user->getId()],
        ];

        if ($communitySlug) {
            $params[] = ['type' => 's', 'value' => $communitySlug];
        }

        $params[] = ['type' => 'i', 'value' => $limit];
        $params[] = ['type' => 'i', 'value' => $offset];

        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT 
       
                `posts`.`id`,
                `posts`.`title`,
                `posts`.`slug`,
                `posts`.`html_content`,
                `posts`.`status_id`,
                `posts`.`likes`,
                `posts`.`dislikes`,
                `posts`.`comments_count`,
                `posts`.`published`,
                `posts`.`created_at`,
                `posts`.`updated_at`,
       
                `lk_account_like_post`.`value` as `user_reaction`,
                
                `accounts`.`name` as `author_name`,
       
                `communities`.`slug` as `community_slug`,
                `communities`.`name` as `community_name`
           
                FROM `posts` 
    
                JOIN `accounts` on `posts`.`author_id` = `accounts`.`id`
                LEFT JOIN `lk_account_like_post` ON `posts`.`slug` = `lk_account_like_post`.`post_slug` AND `lk_account_like_post`.`account_id` = ?
                LEFT JOIN `communities` on `posts`.`community_id` = `communities`.`id`    

                WHERE `posts`.`published` = 1 ' . $this->getCommunityFilter($communitySlug) . '

                ORDER BY `created_at` DESC

                LIMIT ? OFFSET ?',
            $params,
        );

        return PostCollectionFactory::create($this->postDataRefinement($data, $user));
    }

    /**
     * @param string $tagSlug
     * @param int $offset
     * @param int $limit
     * @param int $minRating
     * @param bool $best
     * @param AuthInterface|null $user
     * @return PostCollection
     * @throws AppException
     */
    public function getPostByTag(
        string $tagSlug,
        int $offset,
        int $limit,
        int $minRating,
        bool $best = false,
        ?AuthInterface $user = null
    ): PostCollection {
        $order = $best ? '`rating` DESC ' : '`created_at` DESC ';

        if ($user === null) {
            return $this->getPostByTagNoLikes($tagSlug, $offset, $limit, $minRating, $best);
        }

        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT 
       
                `posts`.`id`,
                `posts`.`title`,
                `posts`.`slug`,
                `posts`.`html_content`,
                `posts`.`status_id`,
                `posts`.`likes`,
                `posts`.`dislikes`,
                `posts`.`rating`,
                `posts`.`comments_count`,
                `posts`.`published`,
                `posts`.`created_at`,
                `posts`.`updated_at`,
       
                `lk_account_like_post`.`value` as `user_reaction`,
                
                `accounts`.`name` as `author_name`,
       
                `communities`.`slug` as `community_slug`,
                `communities`.`name` as `community_name`
           
                FROM `posts` 
    
                JOIN `accounts` ON `posts`.`author_id` = `accounts`.`id`
                JOIN `lk_post_tag` ON `posts`.`id` = `lk_post_tag`.`post_id`
                JOIN `post_tags` ON `lk_post_tag`.`tag_id` = `post_tags`.`id`
                LEFT JOIN `lk_account_like_post` ON `posts`.`slug` = `lk_account_like_post`.`post_slug` AND `lk_account_like_post`.`account_id` = ?
                LEFT JOIN `communities` on `posts`.`community_id` = `communities`.`id`    

                WHERE `posts`.`published` = 1 AND `post_tags`.`slug` = ? AND `posts`.`rating` > ?

                ORDER BY ' . $order . ' LIMIT ? OFFSET ?',
            [
                ['type' => 's', 'value' => $user->getId()],
                ['type' => 's', 'value' => $tagSlug],
                ['type' => 'i', 'value' => $minRating],
                ['type' => 'i', 'value' => $limit],
                ['type' => 'i', 'value' => $offset],
            ],
        );

        return PostCollectionFactory::create($this->postDataRefinement($data, $user));
    }

    /**
     * @param string $tagSlug
     * @param int $offset
     * @param int $limit
     * @param int $minRating
     * @param bool $best
     * @return PostCollection
     * @throws AppException
     */
    public function getPostByTagNoLikes(string $tagSlug, int $offset, int $limit, int $minRating, bool $best = false): PostCollection
    {
        $order = $best ? '`rating` DESC ' : '`created_at` DESC ';

        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT 
       
                `posts`.`id`,
                `posts`.`title`,
                `posts`.`slug`,
                `posts`.`html_content`,
                `posts`.`status_id`,
                `posts`.`likes`,
                `posts`.`dislikes`,
                `posts`.`rating`,
                `posts`.`comments_count`,
                `posts`.`published`,
                `posts`.`created_at`,
                `posts`.`updated_at`,
                
                `accounts`.`name` as `author_name`,
       
                `communities`.`slug` as `community_slug`,
                `communities`.`name` as `community_name`
           
                FROM `posts` 
    
                JOIN `accounts` on `posts`.`author_id` = `accounts`.`id`
                JOIN `lk_post_tag` ON `posts`.`id` = `lk_post_tag`.`post_id`
                JOIN `post_tags` ON `lk_post_tag`.`tag_id` = `post_tags`.`id`
                LEFT JOIN `communities` on `posts`.`community_id` = `communities`.`id`

                WHERE `posts`.`published` = 1 AND `post_tags`.`slug` = ? AND `posts`.`rating` > ?

                ORDER BY ' . $order . ' LIMIT ? OFFSET ?',
            [
                ['type' => 's', 'value' => $tagSlug],
                ['type' => 'i', 'value' => $minRating],
                ['type' => 'i', 'value' => $limit],
                ['type' => 'i', 'value' => $offset],
            ],
        );

        return PostCollectionFactory::create($this->postDataRefinementNoLikes($data));
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
     * @param PostInterface $post
     * @throws AppException
     */
    public function add(PostInterface $post): void
    {
        if ($post->getCommunitySlug() === '' || $post->getCommunitySlug() === PostInterface::NO_COMMUNITY) {
            $communityId = null;
        } else {
            $communityId = $this->communityRepository->getId($post->getCommunitySlug());

            if (!$communityId) {
                throw new AppException(CommunityException::NOT_FOUND . ': ' . $post->getCommunitySlug());
            }
        }

        $this->container->getConnectionPool()->getConnection()->query(
            'INSERT INTO `posts` (`id`, `author_id`, `title`, `slug`, `content`, `html_content`, `status_id`, `comments_count`,
                 `likes`, `dislikes`, `published`, `approved`, `moderated`, `community_id`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
            [
                ['type' => 's', 'value' => $post->getId()],
                ['type' => 's', 'value' => $post->getAuthor()->getId()],
                ['type' => 's', 'value' => $post->getTitle()],
                ['type' => 's', 'value' => $post->getSlug()],
                ['type' => 's', 'value' => $post->getContent()],
                ['type' => 's', 'value' => $post->getHtmlContent()],
                ['type' => 's', 'value' => $post->getStatus()->getId()],
                ['type' => 's', 'value' => $post->getCommentsCount()],
                ['type' => 's', 'value' => $post->getRating()->getLikes()],
                ['type' => 's', 'value' => $post->getRating()->getDislikes()],
                ['type' => 'i', 'value' => (int)$post->isPublished()],
                ['type' => 'i', 'value' => 1], // TODO
                ['type' => 'i', 'value' => 0], // TODO
                ['type' => 's', 'value' => $communityId],
            ]
        );

        foreach ($post->getTags() as $tag) {
            $this->container->getConnectionPool()->getConnection()->query(
                'INSERT INTO `lk_post_tag` (`id`, `post_id`, `tag_id`) VALUES (?, ?, ?)',
                [
                    ['type' => 's', 'value' => Uuid::uuid4()->toString()],
                    ['type' => 's', 'value' => $post->getId()],
                    ['type' => 's', 'value' => $tag->getId()],
                ]
            );
        }
    }

    /**
     * @param string $slug
     * @return string|null
     * @throws AppException
     */
    public function getIdBySlug(string $slug): ?string
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT `id` FROM `posts` WHERE `slug` = ?',
            [['type' => 's', 'value' => $slug]],
            true
        );

        if (!$data || !array_key_exists('id', $data) || !is_string($data['id'])) {
            return null;
        }

        return $data['id'];
    }

    /**
     * @param string $id
     * @throws AppException
     */
    public function increaseCommentsCount(string $id): void
    {
        $this->container->getConnectionPool()->getConnection()->query(
            'UPDATE `posts` SET `comments_count` = `comments_count` + 1 WHERE `id` = ?',
            [['type' => 's', 'value' => $id]],
        );
    }

    /**
     * @param string $slug
     * @param string $accountId
     * @param int $value
     * @param bool $like
     * @throws AppException
     */
    private function changeRating(string $slug, string $accountId, int $value, bool $like = true): void
    {
        $connection = $this->container->getConnectionPool()->getConnection();

        $connection->query(
            'INSERT INTO `lk_account_like_post` (`id`, `account_id`, `post_slug`, `value`) VALUES (?, ?, ?, ?)',
            [
                ['type' => 's', 'value' => Uuid::uuid4()->toString()],
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

    /**
     * @param int $offset
     * @param int $limit
     * @param string|null $communitySlug
     * @return PostCollection
     * @throws AppException
     */
    private function getAllNoLikes(int $offset, int $limit, ?string $communitySlug = null): PostCollection
    {
        $params = [];

        if ($communitySlug) {
            $params[] = ['type' => 's', 'value' => $communitySlug];
        }

        $params[] = ['type' => 'i', 'value' => $limit];
        $params[] = ['type' => 'i', 'value' => $offset];

        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT 
       
                `posts`.`id`,
                `posts`.`title`,
                `posts`.`slug`,
                `posts`.`html_content`,
                `posts`.`status_id`,
                `posts`.`likes`,
                `posts`.`dislikes`,
                `posts`.`comments_count`,
                `posts`.`published`,
                `posts`.`created_at`,
                `posts`.`updated_at`,
                
                `accounts`.`name` as `author_name`,
       
                `communities`.`slug` as `community_slug`,
                `communities`.`name` as `community_name`
           
                FROM `posts` 
    
                JOIN `accounts` on `posts`.`author_id` = `accounts`.`id`
                LEFT JOIN `communities` on `posts`.`community_id` = `communities`.`id`
    
                WHERE `posts`.`published` = 1 ' . $this->getCommunityFilter($communitySlug) . '

                ORDER BY `created_at` DESC

                LIMIT ? OFFSET ?',
            $params,
        );

        return PostCollectionFactory::create($this->postDataRefinementNoLikes($data));
    }

    /**
     * @param string $id
     * @return string|null
     * @throws AppException
     */
    public function getCommunityId(string $id): ?string
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT `community_id` FROM `posts` WHERE `id` = ?',
            [['type' => 's', 'value' => $id]],
            true
        );

        return $data['community_id'] ?? null;
    }

    /**
     * @param array $data
     * @param AuthInterface $user
     * @return array
     */
    private function postDataRefinement(array $data, AuthInterface $user): array
    {
        foreach ($data as &$datum) {

            $datum['author_name'] = $datum['author_name'] ?? '';
            $datum['user_reaction'] = $datum['user_reaction'] ?? 0;
            $isLiked = true;

            if ($user->getName() === $datum['author_name']) {
                $isLiked = false;
            }

            if ($datum['user_reaction'] !== 0) {
                $isLiked = false;
            }

            $datum['is_liked'] = $isLiked;

            // TODO Mock
            $datum['tags'] = [];
        }

        return $data;
    }

    /**
     * @param array $data
     * @return array
     */
    private function postDataRefinementNoLikes(array $data): array
    {
        foreach ($data as &$datum) {
            $datum['is_liked'] = false;
            $datum['user_reaction'] = 0;

            // TODO Mock
            $datum['tags'] = [];
        }

        return $data;
    }

    /**
     * @param string|null $communitySlug
     * @return string
     */
    private function getCommunityFilter(?string $communitySlug = null): string
    {
        if ($communitySlug) {
            return 'AND `communities`.`slug` = ?';
        }

        return '';
    }
}
