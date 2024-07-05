<?php

declare(strict_types=1);

namespace App\Domain\Account\Notice;

use App\Domain\Auth\AuthException;
use WalkWeb\NW\AppException;

class NoticeCollectionFactory
{
    /**
     * @param array $data
     * @return NoticeCollection
     * @throws AuthException
     * @throws NoticeException
     * @throws AppException
     */
    public static function create(array $data): NoticeCollection
    {
        $collection = new NoticeCollection();

        foreach ($data as $noticeData) {
            if (!is_array($noticeData)) {
                throw new AuthException(AuthException::INVALID_NOTICE_DATA);
            }

            $collection->add(NoticeFactory::create($noticeData));
        }

        return $collection;
    }
}
