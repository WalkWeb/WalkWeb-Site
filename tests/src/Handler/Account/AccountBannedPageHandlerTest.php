<?php

declare(strict_types=1);

namespace Test\src\Handler\Account;

use App\Domain\Account\AccountInterface;
use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AccountBannedPageHandlerTest extends AbstractTest
{
    /**
     * @throws AppException
     */
    public function testAccountBannedPageHandlerSuccess(): void
    {
        $request = new Request(['REQUEST_URI' => '/banned'], [], [AccountInterface::AUTH_TOKEN => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a2']);
        $response = $this->createApp()->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression("/Ваш аккаунт заблокирован/", $response->getBody());
        self::assertMatchesRegularExpression("/Вы больше не сможете использовать функционал сайта./", $response->getBody());
        self::assertMatchesRegularExpression("/Разлогиниться/", $response->getBody());
    }

    /**
     * @throws AppException
     */
    public function testAccountBannedPageHandlerNoAuth(): void
    {
        $request = new Request(['REQUEST_URI' => '/banned']);
        $response = $this->createApp()->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression("/Вход/", $response->getBody());
    }

    /**
     * @throws AppException
     */
    public function testAccountBannedPageHandlerNoBanned(): void
    {
        $request = new Request(['REQUEST_URI' => '/banned'], [], [AccountInterface::AUTH_TOKEN => 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1']);
        $response = $this->createApp()->handle($request);

        self::assertEquals(Response::FOUND, $response->getStatusCode());
        self::assertEquals('/', $response->getHeaders()['Location']);
    }
}
