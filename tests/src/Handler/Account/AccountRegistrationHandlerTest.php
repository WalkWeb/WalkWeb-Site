<?php

declare(strict_types=1);

namespace Test\src\Handler\Account;

use App\Domain\Account\AccountException;
use App\Domain\Account\AccountInterface;
use Exception;
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
     * @param string $ref
     * @throws AppException
     */
    public function testAccountRegistrationHandlerSuccess(
        array $server,
        string $login,
        string $email,
        string $password,
        string $floorId,
        string $ip,
        string $ref
    ): void
    {
        $request = new Request(
            $server, ['login' => $login, 'email' => $email, 'password' => $password, 'floor_id' => $floorId],
        );

        $response = $this->app->handle($request);

        self::assertMatchesRegularExpression('/Вы успешно зарегистрировались!/', $response->getBody());
        self::assertEquals(Response::OK, $response->getStatusCode());

        $account = self::getContainer()->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `accounts` WHERE `login` = ?',
            [['type' => 's', 'value' => $login]],
            true
        );

        self::assertEquals($login, $account['login']);
        self::assertEquals($login, $account['name']);
        self::assertEquals($email, $account['email']);
        self::assertEquals((int)$floorId, $account['floor_id']);
        self::assertEquals($ip, $account['ip']);
        self::assertEquals($ref, $account['ref']);

        $mainCharacter = self::getContainer()->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `characters_main` WHERE `account_id` = ?',
            [['type' => 's', 'value' => $account['id']]],
            true
        );

        self::assertEquals(ACTIVE_ERA, $mainCharacter['era_id']);
        self::assertEquals(1, $mainCharacter['level']);
        self::assertEquals(0, $mainCharacter['exp']);
        self::assertEquals(0, $mainCharacter['energy_bonus']);
        self::assertEquals(0, $mainCharacter['upload_bonus']);
        self::assertEquals(0, $mainCharacter['stats_point']);

        // check set main_character_id

        $data = self::getContainer()->getConnectionPool()->getConnection()->query(
            'SELECT `main_character_id` FROM `accounts` WHERE `id` = ?',
            [['type' => 's', 'value' => $account['id']]],
            true
        );

        self::assertEquals($data['main_character_id'], $mainCharacter['id']);
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
            ['REQUEST_URI' => '/registration/main', 'REQUEST_METHOD' => 'POST'],
            ['login' => $login, 'email' => $email, 'password' => $password, 'floor_id' => $floorId],
        );

        $response = $this->app->handle($request);

        self::assertMatchesRegularExpression('/User with this login already exists/', $response->getBody());

        // Check auto substitution
        self::assertMatchesRegularExpression('/DemoUser/', $response->getBody());
        self::assertMatchesRegularExpression('/mail@mail.com/', $response->getBody());

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
            ['REQUEST_URI' => '/registration/main', 'REQUEST_METHOD' => 'POST'],
            ['login' => $login, 'email' => $email, 'password' => $password, 'floor_id' => $floorId],
        );

        $response = $this->app->handle($request);

        self::assertMatchesRegularExpression('/User with this name already exists/', $response->getBody());

        // Check auto substitution
        self::assertMatchesRegularExpression('/NameModerator/', $response->getBody());
        self::assertMatchesRegularExpression('/mail@mail.com/', $response->getBody());

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
            ['REQUEST_URI' => '/registration/main', 'REQUEST_METHOD' => 'POST'],
            ['login' => $login, 'email' => $email, 'password' => $password, 'floor_id' => $floorId],
        );

        $response = $this->app->handle($request);

        self::assertMatchesRegularExpression('/User with this email already exists/', $response->getBody());

        // Check auto substitution
        self::assertMatchesRegularExpression('/User-2/', $response->getBody());
        self::assertMatchesRegularExpression('/mail1@mail.com/', $response->getBody());

        self::assertEquals(Response::OK, $response->getStatusCode());
    }

    /**
     * @throws AppException
     */
    public function testAccountRegistrationHandlerInvalidCsrfToken(): void
    {
        $request = new Request(
            ['REQUEST_URI' => '/registration/main', 'REQUEST_METHOD' => 'POST'],
            ['login' => 'User-11', 'email' => '11mail@mail.com', 'password' => '12345', 'floor_id' => '1'],
        );

        $router = require __DIR__ . '/../../../../routes/web.php';
        $app = new App($router, self::createContainer('dev'));

        $response = $app->handle($request);

        self::assertMatchesRegularExpression('/Invalid csrf-token/', $response->getBody());
        self::assertEquals(Response::OK, $response->getStatusCode());
    }

    /**
     * @throws Exception
     */
    public function testAccountRegistrationHandlerInvalidRef(): void
    {
        $ref = self::generateString(AccountInterface::REF_MAX_LENGTH + 1);

        $request = new Request(
            ['REQUEST_URI' => "/registration/$ref", 'REQUEST_METHOD' => 'POST'],
            ['login' => 'MyUser', 'email' => 'myemail@email.com', 'password' => '12345', 'floor_id' => '1'],
        );

        $response = $this->app->handle($request);

        $error = AccountException::INVALID_REF_LENGTH . AccountInterface::REF_MIN_LENGTH . '-' . AccountInterface::REF_MAX_LENGTH;
        self::assertMatchesRegularExpression("/$error/", $response->getBody());
        self::assertEquals(Response::OK, $response->getStatusCode());
    }

    public function successDataProvider(): array
    {
        return [
            [
                ['REQUEST_URI' => '/registration/main', 'REQUEST_METHOD' => 'POST'],
                'User-10',
                '10mail@mail.com',
                '12345',
                '1',
                'undefined',
                'main',
            ],
            [
                ['REQUEST_URI' => '/registration/ref100', 'REQUEST_METHOD' => 'POST', 'HTTP_CLIENT_IP' => '0.0.0.0'],
                'User-20',
                '20mail@mail.com',
                '12345',
                '2',
                '0.0.0.0',
                'ref100',
            ],
            [
                ['REQUEST_URI' => '/registration/default', 'REQUEST_METHOD' => 'POST', 'HTTP_X_FORWARDED_FOR' => '1.1.1.1'],
                'User-30',
                '30mail@mail.com',
                '123456',
                '1',
                '1.1.1.1',
                'default',
            ],
            [
                ['REQUEST_URI' => '/registration/aaa', 'REQUEST_METHOD' => 'POST', 'REMOTE_ADDR' => '2.2.2.2'],
                'User-40',
                '40mail@mail.com',
                '12345',
                '1',
                '2.2.2.2',
                'aaa',
            ],
        ];
    }
}
