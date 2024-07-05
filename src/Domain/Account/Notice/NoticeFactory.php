<?php

declare(strict_types=1);

namespace App\Domain\Account\Notice;

use Exception;
use WalkWeb\NW\Traits\ValidationTrait;

class NoticeFactory
{
    use ValidationTrait;

    /**
     * Создает объект, реализующий интерфейс NoticeInterface на основе массива параметров
     *
     * @param array $data
     * @return NoticeInterface
     * @throws Exception
     */
    public function create(array $data): NoticeInterface
    {
        self::string($data, 'id', NoticeException::INVALID_ID);
        self::int($data, 'type', NoticeException::INVALID_TYPE);
        self::string($data, 'account_id', NoticeException::INVALID_ACCOUNT_ID);
        self::string($data, 'message', NoticeException::INVALID_MESSAGE);
        self::bool($data, 'view', NoticeException::INVALID_VIEW);
        self::string($data, 'created_at', NoticeException::INVALID_CREATED_AT);

        return new Notice(
            $data['id'],
            $data['type'],
            $data['account_id'],
            $data['message'],
            $data['view'],
            self::date($data['created_at'], NoticeException::INVALID_CREATED_AT),
        );
    }
}
