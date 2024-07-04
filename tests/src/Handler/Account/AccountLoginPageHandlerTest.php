<?php

declare(strict_types=1);

namespace Test\src\Handler\Account;

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
        $response = $this->app->handle($request);

        self::assertMatchesRegularExpression('/Вход/', $response->getBody());
        self::assertEquals(Response::OK, $response->getStatusCode());
    }
}
