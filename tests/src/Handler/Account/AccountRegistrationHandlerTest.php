<?php

declare(strict_types=1);

namespace Test\src\Handler\Account;

use Test\AbstractTest;
use WalkWeb\NW\App;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AccountRegistrationHandlerTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param array $server
     * @param string $login
     * @param string $email
     * @param string $password
     * @param string $floorId
     * @param string $ip
     * @throws AppException
     */
    public function testAccountRegistrationHandlerSuccess(
        array $server,
        string $login,
        string $email,
        string $password,
        string $floorId,
        string $ip
    ): void
    {
        $request = new Request(
            $server, ['login' => $login, 'email' => $email, 'password' => $password, 'floor_id' => $floorId],
        );

        $response = $this->app->handle($request);

        self::assertMatchesRegularExpression('/Вы успешно зарегистрировались!/', $response->getBody());
        self::assertEquals(Response::OK, $response->getStatusCode());

        $data = self::getContainer()->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `accounts` WHERE `login` = ?',
            [['type' => 's', 'value' => $login]],
            true
        );

        self::assertEquals($data['login'], $login);
        self::assertEquals($data['name'], $login);
        self::assertEquals($data['email'], $email);
        self::assertEquals($data['floor_id'], (int)$floorId);
        self::assertEquals($data['ip'], $ip);
    }

    /**
     * @throws AppException
     */
    public function testAccountRegistrationHandlerLoginAlreadyExist(): void
    {
        $login = 'DemoUser';
        $email = 'mail@mail.com';
        $password = '12345';
        $floorId = '1';

        $request = new Request(
            ['REQUEST_URI' => '/registration', 'REQUEST_METHOD' => 'POST'],
            ['login' => $login, 'email' => $email, 'password' => $password, 'floor_id' => $floorId],
        );

        $response = $this->app->handle($request);

        self::assertMatchesRegularExpression('/User with this login already exists/', $response->getBody());
        self::assertEquals(Response::OK, $response->getStatusCode());
    }

    /**
     * @throws AppException
     */
    public function testAccountRegistrationHandlerNameAlreadyExist(): void
    {
        $login = 'NameModerator';
        $email = 'mail@mail.com';
        $password = '12345';
        $floorId = '1';

        $request = new Request(
            ['REQUEST_URI' => '/registration', 'REQUEST_METHOD' => 'POST'],
            ['login' => $login, 'email' => $email, 'password' => $password, 'floor_id' => $floorId],
        );

        $response = $this->app->handle($request);

        self::assertMatchesRegularExpression('/User with this name already exists/', $response->getBody());
        self::assertEquals(Response::OK, $response->getStatusCode());
    }

    /**
     * @throws AppException
     */
    public function testAccountRegistrationHandlerEmailAlreadyExist(): void
    {
        $login = 'User-2';
        $email = 'mail1@mail.com';
        $password = '12345';
        $floorId = '1';

        $request = new Request(
            ['REQUEST_URI' => '/registration', 'REQUEST_METHOD' => 'POST'],
            ['login' => $login, 'email' => $email, 'password' => $password, 'floor_id' => $floorId],
        );

        $response = $this->app->handle($request);

        self::assertMatchesRegularExpression('/User with this email already exists/', $response->getBody());
        self::assertEquals(Response::OK, $response->getStatusCode());
    }

    /**
     * @throws AppException
     */
    public function testAccountRegistrationHandlerInvalidCsrfToken(): void
    {
        $request = new Request(
            ['REQUEST_URI' => '/registration', 'REQUEST_METHOD' => 'POST'],
            ['login' => 'User-11', 'email' => '11mail@mail.com', 'password' => '12345', 'floor_id' => '1'],
        );

        $router = require __DIR__ . '/../../../../routes/web.php';
        $app = new App($router, self::createContainer('dev'));

        $response = $app->handle($request);

        self::assertMatchesRegularExpression('/Invalid csrf-token/', $response->getBody());
        self::assertEquals(Response::OK, $response->getStatusCode());
    }

    public function successDataProvider(): array
    {
        return [
            [
                ['REQUEST_URI' => '/registration', 'REQUEST_METHOD' => 'POST'],
                'User-10',
                '10mail@mail.com',
                '12345',
                '1',
                'undefined',
            ],
            [
                ['REQUEST_URI' => '/registration', 'REQUEST_METHOD' => 'POST', 'HTTP_CLIENT_IP' => '0.0.0.0'],
                'User-20',
                '20mail@mail.com',
                '12345',
                '2',
                '0.0.0.0',
            ],
            [
                ['REQUEST_URI' => '/registration', 'REQUEST_METHOD' => 'POST', 'HTTP_X_FORWARDED_FOR' => '1.1.1.1'],
                'User-30',
                '30mail@mail.com',
                '123456',
                '1',
                '1.1.1.1',
            ],
            [
                ['REQUEST_URI' => '/registration', 'REQUEST_METHOD' => 'POST', 'REMOTE_ADDR' => '2.2.2.2'],
                'User-40',
                '40mail@mail.com',
                '12345',
                '1',
                '2.2.2.2',
            ],
        ];
    }
}
