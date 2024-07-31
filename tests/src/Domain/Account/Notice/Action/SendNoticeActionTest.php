<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Notice\Action;

use App\Domain\Account\Notice\Action\SendNoticeAction;
use App\Domain\Account\Notice\NoticeException;
use App\Domain\Account\Notice\NoticeRepository;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class SendNoticeActionTest extends AbstractTest
{
    /**
     * Тест на создание и сохранение уведомления для пользователя
     *
     * @throws NoticeException
     * @throws AppException
     */
    public function testSendNoticeAction(): void
    {
        $sendNoticeAction = new SendNoticeAction($this->getRepository());

        $accountId = self::DEMO_USER;
        $message = 'Notice message 123';

        $sendNotice = $sendNoticeAction->send($accountId, $message);

        $notice = $this->getRepository()->get($sendNotice->getId());

        self::assertEquals($notice->getId(), $sendNotice->getId());
        self::assertEquals($notice->getTypeId(), $sendNotice->getTypeId());
        self::assertEquals($notice->getAccountId(), $sendNotice->getAccountId());
        self::assertEquals($notice->getMessage(), $sendNotice->getMessage());
        self::assertFalse($sendNotice->isView());
        self::assertEquals(
            $notice->getCreatedAt()->format(self::DATE_FORMAT),
            $sendNotice->getCreatedAt()->format(self::DATE_FORMAT)
        );
    }

    /**
     * @return NoticeRepository
     * @throws AppException
     */
    private function getRepository(): NoticeRepository
    {
        return new NoticeRepository(self::getContainer());
    }
}
