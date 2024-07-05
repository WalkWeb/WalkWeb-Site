<?php

declare(strict_types=1);

namespace App\Domain\Account\Notice;

use App\Domain\Account\AccountException;
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
            'SELECT * FROM `notices` WHERE `id` = ?',
            [['type' => 's', 'value' => $id]],
            true
        );

        if (!$data) {
            throw new AppException(NoticeException::NOT_FOUND, Response::NOT_FOUND);
        }

        return NoticeFactory::create($data);
    }

    /**
     * @param NoticeInterface $notice
     * @throws AppException
     */
    public function save(NoticeInterface $notice): void
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
                ['type' => 'i', 'value' => $notice->getType()],
                ['type' => 's', 'value' => $notice->getAccountId()],
                ['type' => 's', 'value' => $notice->getMessage()],
                ['type' => 'i', 'value' => (int)$notice->isView()],
                ['type' => 's', 'value' => $notice->getCreatedAt()->format('Y-m-d H:i:s')],
            ]
        );
    }
}
