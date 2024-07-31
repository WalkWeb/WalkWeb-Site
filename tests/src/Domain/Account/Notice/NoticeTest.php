<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Notice;

use App\Domain\Account\Notice\Notice;
use App\Domain\Account\Notice\NoticeException;
use DateTime;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class NoticeTest extends AbstractTest
{
    /**
     * Тест на успешное создание уведомления
     *
     * @throws AppException
     */
    public function testNoticeCreateSuccess(): void
    {
        $id = 'd79f1191-d486-46b5-9624-e4a75bdaeeaf';
        $typeId = 1;
        $accountId = '3a08a6c4-ebca-4444-bff5-0eac1634fa15';
        $message = 'Notice message';
        $view = true;
        $createdAt = new DateTime();

        $notice = new Notice($id, $typeId, $accountId, $message, $view, $createdAt);

        self::assertEquals($id, $notice->getId());
        self::assertEquals($typeId, $notice->getTypeId());
        self::assertEquals('Info', $notice->getType());
        self::assertEquals($accountId, $notice->getAccountId());
        self::assertEquals($message, $notice->getMessage());
        self::assertEquals($view, $notice->isView());
        self::assertEquals($createdAt, $notice->getCreatedAt());
        self::assertEquals('только что', $notice->getElapsedCreatedAt());
    }

    /**
     * Тест на ситуацию, когда передан неизвестный тип уведомления
     *
     * @throws AppException
     */
    public function testNoticeCreateUnknownType(): void
    {
        $id = 'd79f1191-d486-46b5-9624-e4a75bdaeeaf';
        $type = 4;
        $accountId = '3a08a6c4-ebca-4444-bff5-0eac1634fa15';
        $message = 'Notice message';
        $view = true;
        $createdAt = new DateTime('2019-08-12 14:00:00');

        $this->expectException(AppException::class);
        $this->expectExceptionMessage(NoticeException::UNKNOWN_TYPE . ': ' . $type);
        new Notice($id, $type, $accountId, $message, $view, $createdAt);
    }
}
