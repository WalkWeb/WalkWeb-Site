<?php

declare(strict_types=1);

namespace Test\src\Handler\Account\Notice;

use App\Domain\Account\AccountInterface;
use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class NoticeCloseAllHandlerTest extends AbstractTest
{
    private const URI = '/notice/all/close';

    /**
     * @throws AppException
     */
    public function testNoticeCloseAllHandlerSuccess(): void
    {
        $authToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $container = $this->app->getContainer();

        // Вначале проверяем, общее количество уведомлений с view = 0
        $data = $container->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `notices` WHERE `view` = 0 AND `account_id` = ?',
            [['type' => 's', 'value' => self::DEMO_USER]]
        );

        self::assertCount(3, $data);

        // Отправляем запрос
        $request = new Request(['REQUEST_URI' => self::URI, 'REQUEST_METHOD' => 'POST'], [], [AccountInterface::AUTH_TOKEN => $authToken]);
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertEquals(self::jsonEncode(['success' => true]), $response->getBody());

        // Проверяем, что общее количество уведомлений с view = 0 стало равно нулю
        $data = $container->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `notices` WHERE `view` = 0 AND `account_id` = ?',
            [['type' => 's', 'value' => self::DEMO_USER]]
        );

        self::assertCount(0, $data);
    }

    /**
     * @throws AppException
     */
    public function testNoticeCloseAllHandlerNoAuth(): void
    {
        $container = $this->app->getContainer();

        // Вначале проверяем, общее количество уведомлений с view = 0
        $data = $container->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `notices` WHERE `view` = 0 AND `account_id` = ?',
            [['type' => 's', 'value' => self::DEMO_USER]]
        );

        self::assertCount(3, $data);

        // Отправляем запрос
        $request = new Request(['REQUEST_URI' => self::URI, 'REQUEST_METHOD' => 'POST']);
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError('Вы не авторизованы', $response);

        // Вначале проверяем, общее количество уведомлений с view = 0 не изменилось
        $data = $container->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `notices` WHERE `view` = 0 AND `account_id` = ?',
            [['type' => 's', 'value' => self::DEMO_USER]]
        );

        self::assertCount(3, $data);
    }
}
