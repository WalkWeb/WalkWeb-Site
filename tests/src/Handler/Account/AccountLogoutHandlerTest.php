<?php

declare(strict_types=1);

namespace Test\src\Handler\Account;

use App\Domain\Account\AccountInterface;
use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AccountLogoutHandlerTest extends AbstractTest
{
    /**
     * Test on logout user (remove authorization token from cookie)
     *
     * @dataProvider successDataProvider
     * @param string $authToken
     * @throws AppException
     */
    public function testAccountLogoutHandler(string $authToken): void
    {
        $request = new Request(['REQUEST_URI' => '/logout'], [], [AccountInterface::AUTH_TOKEN => $authToken]);
        $response = $this->app->handle($request);

        self::assertEquals(Response::FOUND, $response->getStatusCode());
        self::assertEquals([], $this->app->getContainer()->getCookies()->getArray());
    }

    /**
     * @return array
     */
    public function successDataProvider(): array
    {
        return [
            [
                // normal user
                'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1',
            ],
            [
                // banned user
                'VBajfT8P6PFtrkHhCqb7ZNwIFG45a2',
            ],
        ];
    }
}
