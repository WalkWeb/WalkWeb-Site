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
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testAccountListPageHandlerSuccess(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/users/1']);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Пользователи портала/', $response->getBody());
        self::assertMatchesRegularExpression('/DemoUser/', $response->getBody());
    }

    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testAccountListPageHandlerOverPage(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/users/10']);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::NOT_FOUND, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Страница не найдена/', $response->getBody());
    }
}
