<?php

declare(strict_types=1);

namespace Tests\src\Handler\Account;

use Tests\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AccountRegistrationPageHandlerTest extends AbstractTest
{
    /**
     * Test on print registration page
     *
     * @throws AppException
     */
    public function testAccountRegistrationPageHandlerSuccess(): void
    {
        $request = new Request(['REQUEST_URI' => '/registration']);
        $response = $this->app->handle($request);

        self::assertMatchesRegularExpression('/Регистрация/', $response->getBody());
        self::assertEquals(Response::OK, $response->getStatusCode());
    }
}
