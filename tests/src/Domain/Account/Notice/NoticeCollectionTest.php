<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Notice;

use App\Domain\Account\Notice\Notice;
use App\Domain\Account\Notice\NoticeCollection;
use App\Domain\Account\Notice\NoticeException;
use DateTime;
use Test\AbstractTest;

class NoticeCollectionTest extends AbstractTest
{
    /**
     * Тест на успешное создание NoticeCollection
     *
     * @throws NoticeException
     */
    public function testNoticeCollectionCreateSuccess(): void
    {
        $collection = new NoticeCollection();

        self::assertCount(0, $collection);

        $notice1 = new Notice(
            'd79f1191-d486-46b5-9624-e4a75bdaeeaf',
            1,
            '3a08a6c4-ebca-4444-bff5-0eac1634fa15',
            'Notice message #1',
            false,
            new DateTime('2019-08-12 14:00:00')
        );

        $notice2 = new Notice(
            '5f56f5aa-bad5-46ba-91a7-2621762f9c29',
            1,
            'fac622bc-a276-4da5-8f77-bf01b8a2d579',
            'Notice message #2',
            true,
            new DateTime('2019-08-15 18:00:00')
        );

        $collection->add($notice1);
        $collection->add($notice2);

        self::assertCount(2, $collection);

        $i = 0;
        foreach ($collection as $notice) {
            if ($i === 0) {
                self::assertEquals($notice1, $notice);
            }
            if ($i === 1) {
                self::assertEquals($notice2, $notice);
            }
            $i++;
        }

        // Эта проверка добавлена только для покрытия тестами метода key()
        self::assertNull($collection->key());
    }

    /**
     * Тест на ситуацию, когда в коллекцию добавляется уведомление, которое в ней уже существует
     *
     * @throws NoticeException
     */
    public function testNoticeCollectionDoubleNotice(): void
    {
        $collection = new NoticeCollection();

        $notice = new Notice(
            'd79f1191-d486-46b5-9624-e4a75bdaeeaf',
            1,
            '3a08a6c4-ebca-4444-bff5-0eac1634fa15',
            'Notice message #1',
            false,
            new DateTime('2019-08-12 14:00:00')
        );

        $collection->add($notice);

        $this->expectException(NoticeException::class);
        $this->expectExceptionMessage(NoticeException::ALREADY_EXIST);
        $collection->add($notice);
    }
}
