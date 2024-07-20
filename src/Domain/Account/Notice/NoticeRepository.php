<?php

declare(strict_types=1);

namespace App\Domain\Account\Notice;

use App\Domain\Account\AccountException;
use App\Domain\Auth\AuthException;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Container;
use WalkWeb\NW\Response;

class NoticeRepository implements NoticeRepositoryInterface
{
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $id
     * @return NoticeInterface
     * @throws AppException
     * @throws NoticeException
     */
    public function get(string $id): NoticeInterface
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT `id`, `type`, `account_id`, `message`, `view`, `created_at` FROM `notices` WHERE `id` = ?',
            [['type' => 's', 'value' => $id]],
            true
        );

        if (!$data) {
            // TODO NotFoundException
            throw new AppException(NoticeException::NOT_FOUND, Response::NOT_FOUND);
        }

        return NoticeFactory::create($data);
    }

    /**
     * @param string $accountId
     * @return NoticeCollection
     * @throws AppException
     * @throws NoticeException
     * @throws AuthException
     */
    public function getActual(string $accountId): NoticeCollection
    {
        return NoticeCollectionFactory::create(
            $this->container->getConnectionPool()->getConnection()->query(
                'SELECT `id`, `type`, `account_id`, `message`, `view`, `created_at` 
                FROM `notices` WHERE `account_id` = ? AND `view` = 0',
                [['type' => 's', 'value' => $accountId]]
            )
        );
    }

    /**
     * @param string $accountId
     * @param int $offset
     * @param int $limit
     * @return NoticeCollection
     * @throws AppException
     * @throws AuthException
     * @throws NoticeException
     */
    public function getAll(string $accountId, int $offset, int $limit): NoticeCollection
    {
        return NoticeCollectionFactory::create(
            $this->container->getConnectionPool()->getConnection()->query(
                'SELECT `id`, `type`, `account_id`, `message`, `view`, `created_at` 
                FROM `notices` WHERE `account_id` = ? ORDER BY `created_at` DESC LIMIT ? OFFSET ?',
                [
                    ['type' => 's', 'value' => $accountId],
                    ['type' => 'i', 'value' => $limit],
                    ['type' => 'i', 'value' => $offset],
                ]
            )
        );
    }

    /**
     * @param string $accountId
     * @return int
     * @throws AppException
     */
    public function getTotal(string $accountId): int
    {
        return $this->container->getConnectionPool()->getConnection()->query(
            'SELECT count(`id`) as `total` FROM `notices` WHERE `account_id` = ?',
            [['type' => 's', 'value' => $accountId]],
            true
        )['total'];
    }

    /**
     * @param NoticeInterface $notice
     * @throws AppException
     */
    public function add(NoticeInterface $notice): void
    {
        $data = $this->container->getConnectionPool()->getConnection()->query(
            'SELECT `id` FROM `accounts` WHERE `id` = ?',
            [['type' => 's', 'value' => $notice->getAccountId()]],
            true
        );

        if (!$data) {
            throw new AppException(AccountException::NOT_FOUND, Response::NOT_FOUND);
        }

        $this->container->getConnectionPool()->getConnection()->query(
            'INSERT INTO `notices` (`id`, `type`, `account_id`, `message`, `view`, `created_at`) VALUES (?, ?, ?, ?, ?, ?)',
            [
                ['type' => 's', 'value' => $notice->getId()],
                ['type' => 'i', 'value' => $notice->getTypeId()],
                ['type' => 's', 'value' => $notice->getAccountId()],
                ['type' => 's', 'value' => $notice->getMessage()],
                ['type' => 'i', 'value' => (int)$notice->isView()],
                ['type' => 's', 'value' => $notice->getCreatedAt()->format('Y-m-d H:i:s')],
            ]
        );

        $this->container->getConnectionPool()->getConnection()->query(
            'UPDATE `accounts` SET `notice` = 1 WHERE `id` = ?',
            [['type' => 's', 'value' => $notice->getAccountId()]]
        );
    }

    /**
     * @param NoticeInterface $notice
     * @throws AppException
     */
    public function close(NoticeInterface $notice): void
    {
        $this->container->getConnectionPool()->getConnection()->query(
            'UPDATE `notices` SET `view` = 1 WHERE `id` = ?',
            [['type' => 's', 'value' => $notice->getId()]]
        );
    }

    // TODO closeAllByAccountId()
}
