<?php

declare(strict_types=1);

namespace Test\src\Handler\Account;

use App\Domain\Account\AccountException;
use App\Domain\Account\AccountInterface;
use Exception;
use Test\AbstractTest;
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
        $request = new Request(['REQUEST_URI' => '/registration/main']);
        $response = $this->app->handle($request);

        self::assertMatchesRegularExpression('/Регистрация/', $response->getBody());
        self::assertEquals(Response::OK, $response->getStatusCode());
    }

    /**
     * @throws Exception
     */
    public function testAccountRegistrationPageHandlerInvalidRef(): void
    {
        $ref = self::generateString(AccountInterface::REF_MAX_LENGTH + 1);
        $request = new Request(['REQUEST_URI' => "/registration/$ref"]);
        $response = $this->app->handle($request);

        $error = AccountException::INVALID_REF_LENGTH . AccountInterface::REF_MIN_LENGTH . '-' . AccountInterface::REF_MAX_LENGTH;
        self::assertMatchesRegularExpression("/$error/", $response->getBody());
        self::assertEquals(Response::OK, $response->getStatusCode());
    }
}
