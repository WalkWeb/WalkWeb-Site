<?php

declare(strict_types=1);

namespace App\Domain\Account\Notice;

use WalkWeb\NW\AppException;

class NoticeCollectionFactory
{
    /**
     * @param array $data
     * @return NoticeCollection
     * @throws AppException
     */
    public static function create(array $data): NoticeCollection
    {
        $collection = new NoticeCollection();

        foreach ($data as $noticeData) {
            if (!is_array($noticeData)) {
                throw new AppException(NoticeException::INVALID_NOTICE_DATA);
            }

            $collection->add(NoticeFactory::create($noticeData));
        }

        return $collection;
    }
}
