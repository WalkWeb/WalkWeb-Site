<?php

declare(strict_types=1);

namespace Test\src\Domain\Account\Notice;

use App\Domain\Account\Notice\NoticeException;
use App\Domain\Account\Notice\NoticeFactory;
use DateTime;
use Exception;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class NoticeFactoryTest extends AbstractTest
{
    /**
     * Тест на успешное создание уведомления на основе массива параметров
     *
     * @dataProvider successDataProvider
     * @param array $data
     * @throws Exception
     */
    public function testNoticeFactoryCreateSuccess(array $data): void
    {
        $notice = $this->getFactory()->create($data);

        self::assertEquals($data['id'], $notice->getId());
        self::assertEquals($data['type'], $notice->getTypeId());
        self::assertEquals($data['account_id'], $notice->getAccountId());
        self::assertEquals($data['message'], $notice->getMessage());
        self::assertEquals($data['view'], $notice->isView());
        self::assertEquals(new DateTime($data['created_at']), $notice->getCreatedAt());
    }

    /**
     * Тесты на различные варианты невалидных данных
     *
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     * @throws AppException
     */
    public function testNoticeFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        $this->getFactory()->create($data);
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                [
                    'id'         => '7d9593ce-b4c0-483f-a8ac-df0f021cf8ce',
                    'type'       => 1,
                    'account_id' => 'f40647f9-3ed7-4251-9662-94189df0eb25',
                    'message'    => 'message',
                    'view'       => 0,
                    'created_at' => '2019-08-12 14:00:00',
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
            [
                // отсутствует id
                [
                    'type'       => 1,
                    'account_id' => 'f40647f9-3ed7-4251-9662-94189df0eb25',
                    'message'    => 'message',
                    'view'       => 0,
                    'created_at' => '2019-08-12 14:00:00',
                ],
                NoticeException::INVALID_ID,
            ],
            [
                // id некорректного типа
                [
                    'id'         => 123,
                    'type'       => 1,
                    'account_id' => 'f40647f9-3ed7-4251-9662-94189df0eb25',
                    'message'    => 'message',
                    'view'       => 0,
                    'created_at' => '2019-08-12 14:00:00',
                ],
                NoticeException::INVALID_ID,
            ],
            [
                // отсутствует type
                [
                    'id'         => '7d9593ce-b4c0-483f-a8ac-df0f021cf8ce',
                    'account_id' => 'f40647f9-3ed7-4251-9662-94189df0eb25',
                    'message'    => 'message',
                    'view'       => 0,
                    'created_at' => '2019-08-12 14:00:00',
                ],
                NoticeException::INVALID_TYPE,
            ],
            [
                // type некорректного типа
                [
                    'id'         => '7d9593ce-b4c0-483f-a8ac-df0f021cf8ce',
                    'type'       => true,
                    'account_id' => 'f40647f9-3ed7-4251-9662-94189df0eb25',
                    'message'    => 'message',
                    'view'       => 0,
                    'created_at' => '2019-08-12 14:00:00',
                ],
                NoticeException::INVALID_TYPE,
            ],
            [
                // отсутствует account_id
                [
                    'id'         => '7d9593ce-b4c0-483f-a8ac-df0f021cf8ce',
                    'type'       => 1,
                    'message'    => 'message',
                    'view'       => 0,
                    'created_at' => '2019-08-12 14:00:00',
                ],
                NoticeException::INVALID_ACCOUNT_ID,
            ],
            [
                // account_id некорректного типа
                [
                    'id'         => '7d9593ce-b4c0-483f-a8ac-df0f021cf8ce',
                    'type'       => 1,
                    'account_id' => 100,
                    'message'    => 'message',
                    'view'       => 0,
                    'created_at' => '2019-08-12 14:00:00',
                ],
                NoticeException::INVALID_ACCOUNT_ID,
            ],
            [
                // отсутствует message
                [
                    'id'         => '7d9593ce-b4c0-483f-a8ac-df0f021cf8ce',
                    'type'       => 1,
                    'account_id' => 'f40647f9-3ed7-4251-9662-94189df0eb25',
                    'view'       => 0,
                    'created_at' => '2019-08-12 14:00:00',
                ],
                NoticeException::INVALID_MESSAGE,
            ],
            [
                // message некорректного типа
                [
                    'id'         => '7d9593ce-b4c0-483f-a8ac-df0f021cf8ce',
                    'type'       => 1,
                    'account_id' => 'f40647f9-3ed7-4251-9662-94189df0eb25',
                    'message'    =>[ 'message'],
                    'view'       => 0,
                    'created_at' => '2019-08-12 14:00:00',
                ],
                NoticeException::INVALID_MESSAGE,
            ],
            [
                // отсутствует view
                [
                    'id'         => '7d9593ce-b4c0-483f-a8ac-df0f021cf8ce',
                    'type'       => 1,
                    'account_id' => 'f40647f9-3ed7-4251-9662-94189df0eb25',
                    'message'    => 'message',
                    'created_at' => '2019-08-12 14:00:00',
                ],
                NoticeException::INVALID_VIEW,
            ],
            [
                // view некорректного типа
                [
                    'id'         => '7d9593ce-b4c0-483f-a8ac-df0f021cf8ce',
                    'type'       => 1,
                    'account_id' => 'f40647f9-3ed7-4251-9662-94189df0eb25',
                    'message'    => 'message',
                    'view'       => 'true',
                    'created_at' => '2019-08-12 14:00:00',
                ],
                NoticeException::INVALID_VIEW,
            ],
            [
                // отсутствует created_at
                [
                    'id'         => '7d9593ce-b4c0-483f-a8ac-df0f021cf8ce',
                    'type'       => 1,
                    'account_id' => 'f40647f9-3ed7-4251-9662-94189df0eb25',
                    'message'    => 'message',
                    'view'       => 0,
                ],
                NoticeException::INVALID_CREATED_AT,
            ],
            [
                // created_at некорректного типа
                [
                    'id'         => '7d9593ce-b4c0-483f-a8ac-df0f021cf8ce',
                    'type'       => 1,
                    'account_id' => 'f40647f9-3ed7-4251-9662-94189df0eb25',
                    'message'    => 'message',
                    'view'       => 0,
                    'created_at' => 10.5,
                ],
                NoticeException::INVALID_CREATED_AT,
            ],
            [
                // в created_at указана некорректная дата
                [
                    'id'         => '7d9593ce-b4c0-483f-a8ac-df0f021cf8ce',
                    'type'       => 1,
                    'account_id' => 'f40647f9-3ed7-4251-9662-94189df0eb25',
                    'message'    => 'message',
                    'view'       => 0,
                    'created_at' => '1111111111',
                ],
                NoticeException::INVALID_CREATED_AT,
            ],
        ];
    }

    /**
     * @return NoticeFactory
     */
    private function getFactory(): NoticeFactory
    {
        return new NoticeFactory();
    }
}
