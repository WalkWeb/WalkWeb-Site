<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Notice;

use App\Domain\Account\Notice\NoticeCollectionFactory;
use App\Domain\Account\Notice\NoticeException;
use DateTime;
use Exception;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class NoticeCollectionFactoryTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $data
     * @throws Exception
     */
    public function testNoticeCollectionFactorySuccess(array $data): void
    {
        $notices = NoticeCollectionFactory::create($data);

        self::assertSameSize($data, $notices);

        $i = 0;
        foreach ($notices as $notice) {
            self::assertEquals($data[$i]['id'], $notice->getId());
            self::assertEquals($data[$i]['type'], $notice->getTypeId());
            self::assertEquals($data[$i]['account_id'], $notice->getAccountId());
            self::assertEquals($data[$i]['message'], $notice->getMessage());
            self::assertEquals($data[$i]['view'], $notice->isView());
            self::assertEquals(new DateTime($data[$i]['created_at']), $notice->getCreatedAt());

            $i++;
        }
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     */
    public function testNoticeCollectionFactoryFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        NoticeCollectionFactory::create($data);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    [
                        'id'         => '7d9593ce-b4c0-483f-a8ac-df0f021cf801',
                        'type'       => 1,
                        'account_id' => 'f40647f9-3ed7-4251-9662-94189df0eb25',
                        'message'    => 'message 1',
                        'view'       => 0,
                        'created_at' => '2019-08-12 14:00:00',
                    ],
                    [
                        'id'         => '7d9593ce-b4c0-483f-a8ac-df0f021cf802',
                        'type'       => 1,
                        'account_id' => 'f40647f9-3ed7-4251-9662-94189df0eb25',
                        'message'    => 'message 2',
                        'view'       => 0,
                        'created_at' => '2019-08-12 15:00:00',
                    ],
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function failDataProvider(): array
    {
        return [
            // duplicate
            [
                [
                    [
                        'id'         => '7d9593ce-b4c0-483f-a8ac-df0f021cf801',
                        'type'       => 1,
                        'account_id' => 'f40647f9-3ed7-4251-9662-94189df0eb25',
                        'message'    => 'message 1',
                        'view'       => 0,
                        'created_at' => '2019-08-12 14:00:00',
                    ],
                    [
                        'id'         => '7d9593ce-b4c0-483f-a8ac-df0f021cf801',
                        'type'       => 1,
                        'account_id' => 'f40647f9-3ed7-4251-9662-94189df0eb25',
                        'message'    => 'message 2',
                        'view'       => 0,
                        'created_at' => '2019-08-12 15:00:00',
                    ],
                ],
                NoticeException::ALREADY_EXIST,
            ],
            // no array data
            [
                [
                    [
                        'id'         => '7d9593ce-b4c0-483f-a8ac-df0f021cf801',
                        'type'       => 1,
                        'account_id' => 'f40647f9-3ed7-4251-9662-94189df0eb25',
                        'message'    => 'message 1',
                        'view'       => 0,
                        'created_at' => '2019-08-12 14:00:00',
                    ],
                    'data',
                ],
                NoticeException::INVALID_NOTICE_DATA,
            ],
        ];
    }
}
