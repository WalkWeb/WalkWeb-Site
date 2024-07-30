<?php

declare(strict_types=1);

namespace Test\src\Handler\Account\Profile;

use App\Domain\Account\AccountInterface;
use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class ProfilePageHandlerTest extends AbstractTest
{
    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testProfilePageHandlerSuccess(string $template): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $request = new Request(['REQUEST_URI' => '/profile'], [], [AccountInterface::AUTH_TOKEN => $token]);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/DemoUser/', $response->getBody());
    }

    /**
     * @throws AppException
     */
    public function testProfilePageHandlerNoAuth(): void
    {
        $request = new Request(['REQUEST_URI' => '/profile']);
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Войти/', $response->getBody());
    }

    /**
     * @return array
     */
    public function templateDataProvider(): array
    {
        return [
            [
                'default'
            ],
            [
                'inferno'
            ],
        ];
    }
}
