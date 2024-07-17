<?php

declare(strict_types=1);

namespace Test\src\Handler\Account;

use App\Domain\Account\AccountInterface;
use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AccountCheckMailHandlerTest extends AbstractTest
{
    /**
     * Тест на успешное подтверждение email
     *
     * @throws AppException
     */
    public function testCheckEmailHandlerSuccess(): void
    {
        $container = $this->app->getContainer();

        $authToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a3';
        $verifiedToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b3';
        $request = new Request(['REQUEST_URI' => "/check/email/$verifiedToken"], [], [AccountInterface::AUTH_TOKEN => $authToken]);

        // Проверяем данные в базе перед вызовом метода
        $data = $container->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `accounts` WHERE `auth_token` = ?',
            [['type' => 's', 'value' => $authToken]],
            true
        );

        self::assertEquals(0, $data['email_verified']);
        self::assertEquals(0, $data['reg_complete']);

        $response = $this->app->handle($request);

        // В случае успеха происходит переадресация
        self::assertEquals(Response::FOUND, $response->getStatusCode());
        self::assertEquals('/verified/email', $response->getHeaders()['Location']);

        // Проверяем данные в базе после вызова метода
        $data = $container->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `accounts` WHERE `auth_token` = ?',
            [['type' => 's', 'value' => $authToken]],
            true
        );

        self::assertEquals(1, $data['email_verified']);
        self::assertEquals(1, $data['reg_complete']);
    }

    /**
     * Тест на ситуацию, когда к методу обращается неавторизованный пользователь
     *
     * @throws AppException
     */
    public function testCheckEmailHandlerUnauthorized(): void
    {
        $verifiedToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG4xxx';
        $request = new Request(['REQUEST_URI' => "/check/email/$verifiedToken"]);
        $response = $this->createApp()->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Необходима авторизация/', $response->getBody());
        self::assertMatchesRegularExpression('/Перейти на страницу авторизации/', $response->getBody());
    }

    /**
     * Тест на ситуацию, когда указан некорректный токен подтверждения email
     *
     * @throws AppException
     */
    public function testCheckEmailHandlerInvalidToken(): void
    {
        $authToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a3';
        $request = new Request(['REQUEST_URI' => '/check/email/xxxxxxxx'], [], [AccountInterface::AUTH_TOKEN => $authToken]);
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Ошибка подтверждения email/', $response->getBody());
        self::assertMatchesRegularExpression('/Указан некорректный токен подтверждения email/', $response->getBody());
    }

    /**
     * Тест на ситуацию, когда к методу обращается пользовать с уже подтвержденным email - просто переадресация
     *
     * @throws AppException
     */
    public function testCheckEmailHandlerAlreadyCheck(): void
    {
        $authToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $verifiedToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45b1';
        $request = new Request(['REQUEST_URI' => "/check/email/$verifiedToken"], [], [AccountInterface::AUTH_TOKEN => $authToken]);
        $response = $this->app->handle($request);

        self::assertEquals(Response::FOUND, $response->getStatusCode());
        self::assertEquals('/verified/email', $response->getHeaders()['Location']);
    }
}
