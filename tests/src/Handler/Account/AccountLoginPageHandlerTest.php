<?php

declare(strict_types=1);

namespace Test\src\Handler\Account;

use App\Domain\Account\AccountInterface;
use App\Handler\AbstractHandler;
use App\Handler\Account\AccountLoginPageHandler;
use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AccountLoginPageHandlerTest extends AbstractTest
{
    /**
     * Test on print login page
     *
     * @throws AppException
     */
    public function testAccountLoginPageHandlerSuccess(): void
    {
        $request = new Request(['REQUEST_URI' => '/login']);
        $response = $this->createApp()->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Вход/', $response->getBody());
    }

    /**
     * Test on print login page + redirect url
     *
     * @dataProvider redirectDataProvider
     * @param string $header
     * @param string $url
     * @throws AppException
     */
    public function testLoginPageHandlerCustomRedirect(string $header, string $url): void
    {
        $request = new Request(['REQUEST_URI' => '/login', $header => $url]);
        $response = $this->createApp()->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Вход/', $response->getBody());
        self::assertMatchesRegularExpression("/$url/", $response->getBody());
    }

    /**
     * Test on print login page + already auth user
     *
     * @throws AppException
     */
    public function testLoginPageHandlerAlreadyAuth(): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $request = new Request(['REQUEST_URI' => '/login'], [], [AccountInterface::AUTH_TOKEN => $token]);
        $response = $this->createApp()->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/' . AccountLoginPageHandler::ALREADY_AUTH . '/', $response->getBody());
    }

    /**
     * @return array
     */
    public function redirectDataProvider(): array
    {
        return [
            [
                AbstractHandler::REDIRECT_HEADER,
                'custom_redirect_1',
            ],
            [
                'HTTP_X_REDIRECT_URL',
                'custom_redirect_2',
            ],
        ];
    }
}
