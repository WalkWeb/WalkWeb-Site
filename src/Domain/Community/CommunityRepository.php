<?php

declare(strict_types=1);

namespace App\Domain\Community;

use App\Domain\Auth\AuthInterface;
use Ramsey\Uuid\Uuid;
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
     * @param AuthInterface|null $user
     * @return CommunityInterface|null
     * @throws AppException
     */
    public function get(string $slug, ?AuthInterface $user = null): ?CommunityInterface
    {
        if (!$user) {
            return $this->getNotAuth($slug);
        }

        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT 
       
                `communities`.`id`, `level`, `name`, `slug`, `description`, `icon`, `icon_small`, `head_image`, `followers`, 
                `fixed_post_id`, `menu`, `owner_id`, `total_post_count`, `silver_post_count`, `gold_post_count`, 
                `diamond_post_count`, `total_comment_count`, `created_at`, `updated_at`,

                `lk_account_community`.`active` as `is_joined`

                FROM `communities` 

                LEFT JOIN `lk_account_community` ON `communities`.`id` = `lk_account_community`.`community_id` AND `lk_account_community`.`account_id` = ?

                WHERE `slug` = ?',
            [
                    ['type' => 's', 'value' => $user->getId()],
                    ['type' => 's', 'value' => $slug],
                ],
            true
        );

        if (!$data) {
            return null;
        }

        return CommunityFactory::create($data);
    }

    /**
     * @param string $slug
     * @return string
     * @throws AppException
     */
    public function getId(string $slug): string
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT `id` FROM `communities` WHERE `slug` = ?',
            [['type' => 's', 'value' => $slug]],
            true
        );

        if (!$data) {
            return '';
        }

        return (string)$data['id'];
    }

    /**
     * @param string $slug
     * @return string
     * @throws AppException
     */
    public function getName(string $slug): string
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT `name` FROM `communities` WHERE `slug` = ?',
            [['type' => 's', 'value' => $slug]],
            true
        );

        if (!$data) {
            return '';
        }

        return (string)$data['name'];
    }

    /**
     * @return CommunityCollection
     * @throws AppException
     */
    public function getAll(): CommunityCollection
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT 
       
                `id`, `level`, `name`, `slug`, `description`, `icon`, `icon_small`, `head_image`, `followers`, 
                `fixed_post_id`, `menu`, `owner_id`, `total_post_count`, `silver_post_count`, `gold_post_count`, 
                `diamond_post_count`, `total_comment_count`, `created_at`, `updated_at` 

                FROM `communities` 

                ORDER BY `followers` DESC',
        );

        // All communities page no have button "join"
        foreach ($data as &$datum) {
            $datum['is_joined'] = null;
        }

        return CommunityCollectionFactory::create($data);
    }

    /**
     * For future mechanics, it is necessary to distinguish between completely new user of the community, from user who
     * has previously joined, but then leave
     *
     * For this reason, an additional parameter active is used
     *
     * @param string $accountId
     * @param string $communityId
     * @throws AppException
     */
    public function join(string $accountId, string $communityId): void
    {
        if ($id = $this->existMember($accountId, $communityId)) {
            $this->container->getConnectionPool()->getConnection()->query(
                'UPDATE `lk_account_community` SET `active` = 1 WHERE `id` = ?',
                [['type' => 's', 'value' => $id]]
            );
        } else {
            $this->container->getConnectionPool()->getConnection()->query(
                'INSERT INTO `lk_account_community` (`id`, `account_id`, `community_id`) VALUES (?, ?, ?)',
                [
                    ['type' => 's', 'value' => Uuid::uuid4()->toString()],
                    ['type' => 's', 'value' => $accountId],
                    ['type' => 's', 'value' => $communityId],
                ]
            );
        }

        $this->container->getConnectionPool()->getConnection()->query(
            'UPDATE `communities` SET `followers` = `followers` + 1 WHERE `id` = ?',
            [['type' => 's', 'value' => $communityId]]
        );
    }

    /**
     * @param string $accountId
     * @param string $communityId
     * @throws AppException
     */
    public function leave(string $accountId, string $communityId): void
    {
        if ($id = $this->existMember($accountId, $communityId)) {
            $this->container->getConnectionPool()->getConnection()->query(
                'UPDATE `lk_account_community` SET `active` = 0 WHERE `id` = ?',
                [['type' => 's', 'value' => $id]]
            );

            $this->container->getConnectionPool()->getConnection()->query(
                'UPDATE `communities` SET `followers` = `followers` - 1 WHERE `id` = ?',
                [['type' => 's', 'value' => $communityId]]
            );

        } else {
            throw new AppException(CommunityException::MEMBER_NOT_FOUND);
        }
    }

    /**
     * @param string $id
     * @throws AppException
     */
    public function increaseCommentCount(string $id): void
    {
        $this->container->getConnectionPool()->getConnection()->query(
            'UPDATE `communities` SET `total_comment_count` = `total_comment_count` + 1 WHERE `id` = ?',
            [['type' => 's', 'value' => $id]],
        );
    }

    /**
     * @param string $accountId
     * @param string $communityId
     * @return string|null
     * @throws AppException
     */
    private function existMember(string $accountId, string $communityId): ?string
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT `id` FROM `lk_account_community` WHERE `account_id` = ? AND `community_id` = ?',
            [
                ['type' => 's', 'value' => $accountId],
                ['type' => 's', 'value' => $communityId],
            ],
            true
        );

        if (!$data) {
            return null;
        }

        return (string)$data['id'];
    }

    /**
     * @param string $slug
     * @return CommunityInterface|null
     * @throws AppException
     */
    private function getNotAuth(string $slug): ?CommunityInterface
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

        $data['is_joined'] = null;

        return CommunityFactory::create($data);
    }
}
