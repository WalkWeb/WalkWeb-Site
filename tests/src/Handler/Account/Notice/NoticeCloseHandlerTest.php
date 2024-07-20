<?php

declare(strict_types=1);

namespace Test\src\Handler\Account\Notice;

use App\Domain\Account\AccountInterface;
use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class NoticeCloseHandlerTest extends AbstractTest
{
    private const URI = '/notice/close/';

    /**
     * @throws AppException
     */
    public function testNoticeCloseHandlerSuccess(): void
    {
        $authToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $noticeId = 'd92bce7f-112d-442c-8a75-bf440f477af1';
        $container = $this->app->getContainer();

        // Вначале проверяем, что уведомление view = 0
        $data = $container->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `notices` WHERE `id` = ?',
            [['type' => 's', 'value' => $noticeId]],
            true
        );

        self::assertEquals(0, $data['view']);

        // Отправляем запрос
        $request = new Request(['REQUEST_URI' => self::URI . $noticeId, 'REQUEST_METHOD' => 'POST'], [], [AccountInterface::AUTH_TOKEN => $authToken]);
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertEquals(self::jsonEncode(['success' => true]), $response->getBody());

        // Проверяем, что уведомление view = 1
        $data = $container->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `notices` WHERE `id` = ?',
            [['type' => 's', 'value' => $noticeId]],
            true
        );

        self::assertEquals(1, $data['view']);
    }

    /**
     * @throws AppException
     */
    public function testNoticeCloseHandlerNoAuth(): void
    {
        $noticeId = 'd92bce7f-112d-442c-8a75-bf440f477af1';
        $container = $this->app->getContainer();

        // Вначале проверяем, что уведомление view = 0
        $data = $container->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `notices` WHERE `id` = ?',
            [['type' => 's', 'value' => $noticeId]],
            true
        );

        self::assertEquals(0, $data['view']);

        // Отправляем запрос
        $request = new Request(['REQUEST_URI' => self::URI . $noticeId, 'REQUEST_METHOD' => 'POST']);
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError('Вы не авторизованы', $response);

        // Проверяем, что данные в базе не изменились
        $data = $container->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `notices` WHERE `id` = ?',
            [['type' => 's', 'value' => $noticeId]],
            true
        );

        self::assertEquals(0, $data['view']);
    }

    /**
     * @throws AppException
     */
    public function testNoticeCloseHandlerUnknownId(): void
    {
        $authToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $noticeId = 'd92bce7f-112d-442c-8a75-bf440f477xxx';

        // Отправляем запрос
        $request = new Request(['REQUEST_URI' => self::URI . $noticeId, 'REQUEST_METHOD' => 'POST'], [], [AccountInterface::AUTH_TOKEN => $authToken]);
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError('Уведомление не найдено', $response);
    }

    /**
     * @throws AppException
     */
    public function testNoticeCloseHandlerNoOwner(): void
    {
        $authToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a3';
        $noticeId = 'd92bce7f-112d-442c-8a75-bf440f477af1';
        $container = $this->app->getContainer();

        // Вначале проверяем, что уведомление view = 0
        $data = $container->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `notices` WHERE `id` = ?',
            [['type' => 's', 'value' => $noticeId]],
            true
        );

        self::assertEquals(0, $data['view']);

        // Отправляем запрос
        $request = new Request(['REQUEST_URI' => self::URI . $noticeId, 'REQUEST_METHOD' => 'POST'], [], [AccountInterface::AUTH_TOKEN => $authToken]);
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError('Вы обращаетесь к чужому уведомлению', $response);

        // Проверяем, что данные в базе не изменились
        $data = $container->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `notices` WHERE `id` = ?',
            [['type' => 's', 'value' => $noticeId]],
            true
        );

        self::assertEquals(0, $data['view']);
    }
}
