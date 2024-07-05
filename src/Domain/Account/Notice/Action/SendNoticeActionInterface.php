<?php

declare(strict_types=1);

namespace App\Domain\Account\Notice\Action;

use App\Domain\Account\Notice\NoticeException;
use App\Domain\Account\Notice\NoticeInterface;

interface SendNoticeActionInterface
{
    /**
     * Создает и сохраняет уведомление для пользователя
     *
     * @param string $accountId
     * @param string $message
     * @param int $type
     * @return NoticeInterface
     * @throws NoticeException
     */
    public function send(string $accountId, string $message, int $type = NoticeInterface::TYPE_INFO): NoticeInterface;
}
