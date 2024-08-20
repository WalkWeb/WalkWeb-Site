<?php

declare(strict_types=1);

namespace App\Domain\Account\Notice\Action;

use App\Domain\Account\Notice\NoticeInterface;
use WalkWeb\NW\AppException;

interface SendNoticeActionInterface
{
    /**
     * Создает и сохраняет уведомление для пользователя
     *
     * @param string $accountId
     * @param string $message
     * @param int $type
     * @param bool $print
     * @return NoticeInterface
     * @throws AppException
     */
    public function send(
        string $accountId,
        string $message,
        int $type = NoticeInterface::TYPE_INFO,
        bool $print = true
    ): NoticeInterface;
}
