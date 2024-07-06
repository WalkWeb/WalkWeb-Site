<?php

declare(strict_types=1);

namespace Test\src\Middleware;

use App\Domain\Account\AccountInterface;
use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AuthMiddlewareTest extends AbstractTest
{
    /**
     * @throws AppException
     */
    public function testAuthMiddleware(): void
    {
        $request = new Request(['REQUEST_URI' => '/'], [], [AccountInterface::AUTH_TOKEN => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1']);
        $app = $this->createApp();

        self::assertFalse($app->getContainer()->exist('user'));

        $response = $app->handle($request);

        self::assertMatchesRegularExpression('/Заголовок поста #1/', $response->getBody());
        self::assertEquals(Response::OK, $response->getStatusCode());

        self::assertTrue($app->getContainer()->exist('user'));

        $user = $app->getContainer()->getUser();

        self::assertEquals('DemoUser', $user->getName());
    }
}
