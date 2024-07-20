<?php

declare(strict_types=1);

namespace Test\src\Handler\Account;

use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AccountListPageHandlerTest extends AbstractTest
{
    /**
     * @throws AppException
     */
    public function testAccountListPageHandlerSuccess(): void
    {
        $request = new Request(['REQUEST_URI' => '/users/1']);
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Пользователи портала/', $response->getBody());
        self::assertMatchesRegularExpression('/DemoUser/', $response->getBody());
    }

    /**
     * @throws AppException
     */
    public function testAccountListPageHandlerOverPage(): void
    {
        $request = new Request(['REQUEST_URI' => '/users/10']);
        $response = $this->app->handle($request);

        self::assertEquals(Response::NOT_FOUND, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Page not found/', $response->getBody());
    }
}
