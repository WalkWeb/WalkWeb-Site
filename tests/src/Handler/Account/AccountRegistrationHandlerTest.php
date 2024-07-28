<?php

declare(strict_types=1);

namespace Test\src\Handler\Account;

use App\Domain\Account\AccountException;
use App\Domain\Account\AccountInterface;
use App\Domain\Account\Character\CharacterException;
use App\Domain\Account\Notice\NoticeInterface;
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
     * @param array $body
     * @param string $expectedReferral
     * @param string $expectedIp
     * @throws AppException
     */
    public function testAccountRegistrationHandlerSuccess(
        array $server,
        array $body,
        string $expectedReferral,
        string $expectedIp
    ): void
    {
        $request = new Request(
            $server, $body,
        );

        $response = $this->app->handle($request);

        self::assertEquals(Response::FOUND, $response->getStatusCode());
        self::assertEquals('/verified/email', $response->getHeaders()['Location']);

        $account = self::getContainer()->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `accounts` WHERE `login` = ?',
            [['type' => 's', 'value' => $body['login']]],
            true
        );

        self::assertEquals($body['login'], $account['login']);
        self::assertEquals($body['login'], $account['name']);
        self::assertEquals($body['email'], $account['email']);
        self::assertEquals((int)$body['floor_id'], $account['floor_id']);
        self::assertEquals($expectedIp, $account['ip']);
        self::assertEquals($expectedReferral, $account['ref']);

        // check main_character

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

        // check character

        $character = self::getContainer()->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `characters` WHERE `character_main_id` = ?',
            [['type' => 's', 'value' => $data['main_character_id']]],
            true
        );

        self::assertEquals($data['main_character_id'], $character['character_main_id']);
        self::assertEquals(ACTIVE_SEASON, $character['season_id']);
        self::assertEquals($body['genesis_id'], $character['genesis_id']);
        self::assertEquals($body['profession_id'], $character['profession_id']);
        self::assertEquals($body['avatar_id'], $character['avatar_id']);
        self::assertEquals($body['floor_id'], $character['floor_id']);
        self::assertEquals(1, $character['level']);
        self::assertEquals(0, $character['exp']);
        self::assertEquals(0, $character['stats_point']);
        self::assertEquals(0, $character['skill_point']);

        // check set character_id

        $data = self::getContainer()->getConnectionPool()->getConnection()->query(
            'SELECT `character_id` FROM `accounts` WHERE `id` = ?',
            [['type' => 's', 'value' => $account['id']]],
            true
        );

        self::assertEquals($data['character_id'], $character['id']);

        // check notice

        $data = self::getContainer()->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `notices` WHERE `account_id` = ?',
            [['type' => 's', 'value' => $account['id']]],
        );

        self::assertCount(1, $data);
        self::assertEquals(NoticeInterface::TYPE_INFO, $data[0]['type']);
        self::assertEquals($account['id'], $data[0]['account_id']);
        self::assertEquals(NoticeInterface::REGISTER_START, $data[0]['message']);
        self::assertEquals(1, $data[0]['view']);
    }

    /**
     * @throws AppException
     */
    public function testAccountRegistrationHandlerLoginAlreadyExist(): void
    {
        $request = new Request(
            ['REQUEST_URI' => '/registration/main', 'REQUEST_METHOD' => 'POST'],
            [
                'login'         => 'DemoUser',
                'email'         => 'mail@mail.com',
                'password'      => '12345',
                'floor_id'      => '1',
                'genesis_id'    => '4',
                'profession_id' => '3',
                'avatar_id'     => '21',
            ],
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
        $request = new Request(
            ['REQUEST_URI' => '/registration/main', 'REQUEST_METHOD' => 'POST'],
            [
                'login'         => 'NameModerator',
                'email'         => 'mail@mail.com',
                'password'      => '12345',
                'floor_id'      => '1',
                'genesis_id'    => '4',
                'profession_id' => '3',
                'avatar_id'     => '21',
            ],
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
        $request = new Request(
            ['REQUEST_URI' => '/registration/main', 'REQUEST_METHOD' => 'POST'],
            [
                'login'         => 'User-2',
                'email'         => 'mail1@mail.com',
                'password'      => '12345',
                'floor_id'      => '1',
                'genesis_id'    => '4',
                'profession_id' => '3',
                'avatar_id'     => '21',
            ],
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
    public function testAccountRegistrationHandlerUnknownGenesis(): void
    {
        $request = new Request(
            ['REQUEST_URI' => '/registration/main', 'REQUEST_METHOD' => 'POST'],
            [
                'login'         => 'User-50',
                'email'         => 'dufo@mail.com',
                'password'      => '12345',
                'floor_id'      => '1',
                'genesis_id'    => '54',
                'profession_id' => '3',
                'avatar_id'     => '31',
            ],
        );

        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/' . CharacterException::UNKNOWN_GENESIS_ID . '/', $response->getBody());
    }

    /**
     * @throws AppException
     */
    public function testAccountRegistrationHandlerUnknownProfession(): void
    {
        $request = new Request(
            ['REQUEST_URI' => '/registration/main', 'REQUEST_METHOD' => 'POST'],
            [
                'login'         => 'User-50',
                'email'         => 'dufo@mail.com',
                'password'      => '12345',
                'floor_id'      => '1',
                'genesis_id'    => '1',
                'profession_id' => '6',
                'avatar_id'     => '31',
            ],
        );

        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/' . CharacterException::UNKNOWN_PROFESSION_ID . '/', $response->getBody());
    }

    /**
     * @throws AppException
     */
    public function testAccountRegistrationHandlerUnknownAvatar(): void
    {
        $request = new Request(
            ['REQUEST_URI' => '/registration/main', 'REQUEST_METHOD' => 'POST'],
            [
                'login'         => 'User-50',
                'email'         => 'dufo@mail.com',
                'password'      => '12345',
                'floor_id'      => '1',
                'genesis_id'    => '1',
                'profession_id' => '1',
                'avatar_id'     => '40',
            ],
        );

        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/' . CharacterException::UNKNOWN_AVATAR_ID . '/', $response->getBody());
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
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '31',
                ],
                'main',
                'undefined',
            ],
            [
                ['REQUEST_URI' => '/registration/ref100', 'REQUEST_METHOD' => 'POST', 'HTTP_CLIENT_IP' => '0.0.0.0'],
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '32',
                ],
                'ref100',
                '0.0.0.0',
            ],
            [
                ['REQUEST_URI' => '/registration/default', 'REQUEST_METHOD' => 'POST', 'HTTP_X_FORWARDED_FOR' => '1.1.1.1'],
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '33',
                ],
                'default',
                '1.1.1.1',
            ],
            [
                ['REQUEST_URI' => '/registration/aaa', 'REQUEST_METHOD' => 'POST', 'REMOTE_ADDR' => '2.2.2.2'],
                [
                    'login'         => 'login',
                    'email'         => 'email@mail.com',
                    'password'      => 'password',
                    'floor_id'      => '2',
                    'genesis_id'    => '3',
                    'profession_id' => '3',
                    'avatar_id'     => '34',
                ],
                'aaa',
                '2.2.2.2',
            ],
        ];
    }
}
