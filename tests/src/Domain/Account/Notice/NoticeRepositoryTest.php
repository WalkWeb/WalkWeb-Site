<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Notice;

use App\Domain\Account\Notice\Notice;
use App\Domain\Account\Notice\NoticeException;
use DateTime;
use Test\AbstractTest;
use Test\Mock\Domain\Account\Notice\MockNoticeRepository;

class NoticeRepositoryTest extends AbstractTest
{
    /**
     * Тест на сохранение и получение уведомления в моке на реализацию NoticeRepositoryInterface
     *
     * @throws NoticeException
     */
    public function testNoticeRepositorySave(): void
    {
        $id = 'd79f1191-d486-46b5-9624-e4a75bdaeeaf';
        $type = 1;
        $accountId = '3a08a6c4-ebca-4444-bff5-0eac1634fa15';
        $message = 'Notice message';
        $view = true;
        $createdAt = new DateTime('2019-08-12 14:00:00');

        $repository = new MockNoticeRepository();

        $repository->save(new Notice(
            $id,
            $type,
            $accountId,
            $message,
            $view,
            $createdAt
        ));

        $notice = $repository->get($id);

        self::assertEquals($id, $notice->getId());
        self::assertEquals($type, $notice->getType());
        self::assertEquals($accountId, $notice->getAccountId());
        self::assertEquals($message, $notice->getMessage());
        self::assertEquals($view, $notice->isView());
        self::assertEquals($createdAt, $notice->getCreatedAt());
    }
}