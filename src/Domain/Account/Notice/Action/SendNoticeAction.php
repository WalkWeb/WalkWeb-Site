<?php

declare(strict_types=1);

namespace App\Domain\Account\Notice\Action;

use App\Domain\Account\Notice\Notice;
use App\Domain\Account\Notice\NoticeException;
use App\Domain\Account\Notice\NoticeInterface;
use App\Domain\Account\Notice\NoticeRepositoryInterface;
use DateTime;
use Ramsey\Uuid\Uuid;

// TODO Добавить в контейнер

class SendNoticeAction implements SendNoticeActionInterface
{
    private NoticeRepositoryInterface $noticeRepository;

    public function __construct(NoticeRepositoryInterface $noticeRepository)
    {
        $this->noticeRepository = $noticeRepository;
    }

    /**
     * Создает уведомление для пользователя
     *
     * @param string $accountId
     * @param string $message
     * @param int $type
     * @param bool $print
     * @return NoticeInterface
     * @throws NoticeException
     */
    public function send(string $accountId, string $message, int $type = NoticeInterface::TYPE_INFO, bool $print = true): NoticeInterface
    {
        $notice = new Notice(
            (string)Uuid::uuid4(),
            $type,
            $accountId,
            $message,
            !$print,
            new DateTime()
        );

        $this->noticeRepository->add($notice);

        return $notice;
    }
}
