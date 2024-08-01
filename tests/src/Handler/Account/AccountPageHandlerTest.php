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
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testAccountPageHandlerSuccess(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/u/DemoUser']);
        $response = $this->createApp($template)->handle($request);

        self::assertMatchesRegularExpression('/DemoUser/', $response->getBody());
        self::assertEquals(Response::OK, $response->getStatusCode());
    }

    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testAccountPageHandlerUserNotFound(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/u/MussUser']);
        $response = $this->createApp($template)->handle($request);

        self::assertMatchesRegularExpression('/Пользователь не найден/', $response->getBody());
        self::assertEquals(Response::NOT_FOUND, $response->getStatusCode());
    }
}
