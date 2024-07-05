<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Notice\Action;

use App\Domain\Account\Notice\Action\SendNoticeAction;
use App\Domain\Account\Notice\NoticeException;
use App\Domain\Account\Notice\NoticeInterface;
use ReflectionClass;
use ReflectionException;
use Test\AbstractTest;
use Test\Mock\Domain\Account\Notice\MockNoticeRepository;

class SendNoticeActionTest extends AbstractTest
{
    /**
     * Тест на создание и сохранение (как бы сохранение) уведомления для пользователя
     *
     * @throws NoticeException
     * @throws ReflectionException
     */
    public function testSendNoticeAction(): void
    {
        $sendNoticeAction = new SendNoticeAction($mockRepository = new MockNoticeRepository());

        $accountId = '3a08a6c4-ebca-4444-bff5-0eac1634fa15';
        $message = 'Notice message';

        // Создание уведомление и сохранение его
        $sendNoticeAction->send($accountId, $message);

        // Чтобы получить уведомление из репозитория, используем рефлексию
        $reflectionClass = new ReflectionClass($mockRepository);
        $reflectionProperty = $reflectionClass->getProperty('notices');
        $reflectionProperty->setAccessible(true);

        $notices = $reflectionProperty->getValue($mockRepository);

        self::assertCount(1, $notices);

        /** @var NoticeInterface $notice */
        foreach ($notices as $notice) {
            self::assertEquals($accountId, $notice->getAccountId());
            self::assertEquals($message, $notice->getMessage());
            self::assertEquals(NoticeInterface::TYPE_INFO, $notice->getType());
        }
    }
}
