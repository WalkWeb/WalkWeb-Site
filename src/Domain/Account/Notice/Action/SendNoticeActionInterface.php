<?php

declare(strict_types=1);

namespace App\Domain\Account\Notice\Action;

use App\Domain\Account\Notice\NoticeException;
use App\Domain\Account\Notice\NoticeInterface;

interface SendNoticeActionInterface
{
    public const REGISTER_START = 'Начало регистрации на портале';

    /**
     * Создает и сохраняет уведомление для пользователя
     *
     * @param string $accountId
     * @param string $message
     * @param int $type
     * @param bool $print
     * @return NoticeInterface
     * @throws NoticeException
     */
    public function send(
        string $accountId,
        string $message,
        int $type = NoticeInterface::TYPE_INFO,
        bool $print = true
    ): NoticeInterface;
}
