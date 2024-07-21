<?php

declare(strict_types=1);

namespace Test\src\Handler\Temporary;

use App\Domain\Account\AccountInterface;
use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class ReducedEnergyHandlerTest extends AbstractTest
{
    private const URI = '/reduced/energy';

    /**
     * @throws AppException
     */
    public function testReducedEnergyHandlerSuccess(): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $request = new Request(['REQUEST_URI' => self::URI, 'REQUEST_METHOD' => 'POST'], [], [AccountInterface::AUTH_TOKEN => $token]);
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertEquals(self::jsonEncode(['success' => true]), $response->getBody());
    }

    /**
     * @throws AppException
     */
    public function testReducedEnergyHandlerFail(): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $request = new Request(['REQUEST_URI' => self::URI, 'REQUEST_METHOD' => 'POST'], [], [AccountInterface::AUTH_TOKEN => $token]);

        $this->app->handle($request);
        $this->app->handle($request);
        $this->app->handle($request);
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError('No enough energy. Have 30 need 40', $response);
    }

    /**
     * @throws AppException
     */
    public function testReducedEnergyHandlerNoAuth(): void
    {
        $request = new Request(['REQUEST_URI' => self::URI, 'REQUEST_METHOD' => 'POST']);
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Логин/', $response->getBody());
    }
}
