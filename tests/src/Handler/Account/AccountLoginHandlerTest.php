<?php

declare(strict_types=1);

namespace Test\src\Handler\Account;

use App\Domain\Account\AccountException;
use App\Domain\Account\AccountInterface;
use Test\AbstractTest;
use WalkWeb\NW\App;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AccountLoginHandlerTest extends AbstractTest
{
    /**
     * Тест на успешную авторизацию
     *
     * @throws AppException
     */
    public function testAccountLoginHandlerSuccess(): void
    {
        $request = new Request(
            ['REQUEST_URI' => '/login', 'REQUEST_METHOD' => 'POST'],
            ['login' => 'DemoUser', 'password' => '12345'],
        );
        $response = $this->app->handle($request);
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';

        self::assertEquals(Response::FOUND, $response->getStatusCode());
        self::assertEquals([AccountInterface::AUTH_TOKEN => $token], $this->app->getContainer()->getCookies()->getArray());
    }

    /**
     * Тест на различные ошибки при авторизации
     *
     * @dataProvider failDataProvider
     * @param string $login
     * @param string $password
     * @param string $error
     * @throws AppException
     */
    public function testAccountLoginHandlerFail(string $login, string $password, string $error): void
    {
        $request = new Request(
            ['REQUEST_URI' => '/login', 'REQUEST_METHOD' => 'POST'],
            ['login' => $login, 'password' => $password],
        );
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression("/$error/", $response->getBody());
        self::assertEquals([], $this->app->getContainer()->getCookies()->getArray());
    }

    /**
     * @throws AppException
     */
    public function testAccountLoginHandlerInvalidCsrfToken(): void
    {
        $request = new Request(
            ['REQUEST_URI' => '/login', 'REQUEST_METHOD' => 'POST'],
            ['login' => 'DemoUser', 'password' => '12345'],
        );

        $router = require __DIR__ . '/../../../../routes/web.php';
        $app = new App($router, self::createContainer('dev'));

        $response = $app->handle($request);

        self::assertMatchesRegularExpression('/Invalid csrf-token/', $response->getBody());
        self::assertEquals(Response::OK, $response->getStatusCode());
    }

    /**
     * @return array
     */
    public function failDataProvider(): array
    {
        return [
            // Невалидный логин
            [
                'xxx',
                '12345',
                AccountException::INVALID_LOGIN_LENGTH,
            ],
            // Неправильный логин
            [
                'Login-',
                '12345',
                AccountException::INVALID_LOGIN_OR_PASSWORD,
            ],
            // Неправильный пароль
            [
                'Login-1',
                '123456',
                AccountException::INVALID_LOGIN_OR_PASSWORD,
            ],
            // Неправильный логин и пароль
            [
                'Login-11',
                '123456',
                AccountException::INVALID_LOGIN_OR_PASSWORD,
            ],
        ];
    }
}
