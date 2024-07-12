<?php

declare(strict_types=1);

namespace Test\src\Handler\Account;

use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AccountPageHandlerTest extends AbstractTest
{
    /**
     * @throws AppException
     */
    public function testAccountPageHandlerSuccess(): void
    {
        $request = new Request(['REQUEST_URI' => '/u/DemoUser']);
        $response = $this->app->handle($request);

        self::assertMatchesRegularExpression('/Логин: DemoUser/', $response->getBody());
        self::assertEquals(Response::OK, $response->getStatusCode());
    }

    /**
     * @throws AppException
     */
    public function testAccountPageHandlerUserNotFound(): void
    {
        $request = new Request(['REQUEST_URI' => '/u/MussUser']);
        $response = $this->app->handle($request);

        self::assertMatchesRegularExpression('/Пользователь не найден/', $response->getBody());
        self::assertEquals(Response::NOT_FOUND, $response->getStatusCode());
    }
}
