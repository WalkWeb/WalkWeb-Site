<?php

declare(strict_types=1);

namespace Test\src\Handler\Temporary;

use App\Domain\Account\AccountInterface;
use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AddExpHandlerTest extends AbstractTest
{
    /**
     * @throws AppException
     */
    public function testAddExpHandlerSuccess(): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $request = new Request(['REQUEST_URI' => '/add/exp'], [], [AccountInterface::AUTH_TOKEN => $token]);
        $response = $this->app->handle($request);

        self::assertEquals(Response::FOUND, $response->getStatusCode());
        self::assertEquals('/profile', $response->getHeaders()['Location']);
    }

    /**
     * @throws AppException
     */
    public function testAddExpHandlerNoAuth(): void
    {
        $request = new Request(['REQUEST_URI' => '/add/exp']);
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Логин/', $response->getBody());
    }
}
