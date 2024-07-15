<?php

declare(strict_types=1);

namespace Test\src\Handler\Account\Notice;

use App\Domain\Account\AccountInterface;
use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class AccountNoticePageHandlerTest extends AbstractTest
{
    /**
     * Test on success print notices page
     *
     * @throws AppException
     */
    public function testAccountNoticePageHandlerSuccess(): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $request = new Request(['REQUEST_URI' => '/notices/1'], [], [AccountInterface::AUTH_TOKEN => $token]);
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Ваши уведомления/', $response->getBody());
        self::assertMatchesRegularExpression('/notice message 5/', $response->getBody());
    }

    /**
     * Test on print no notices page
     *
     * @throws AppException
     */
    public function testAccountNoticePageHandlerEmpty(): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a4';
        $request = new Request(['REQUEST_URI' => '/notices/1'], [], [AccountInterface::AUTH_TOKEN => $token]);
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Ваши уведомления/', $response->getBody());
        self::assertMatchesRegularExpression('/Пока у вас нет уведомлений/', $response->getBody());
    }

    /**
     * Test on no authorise get page
     *
     * @throws AppException
     */
    public function testAccountNoticePageHandlerNoAuth(): void
    {
        $request = new Request(['REQUEST_URI' => '/notices/1']);
        $response = $this->createApp()->handle($request);

        self::assertEquals(Response::FOUND, $response->getStatusCode());
        self::assertEquals('/login', $response->getHeaders()['Location']);
    }

    /**
     * Test on get no exist page
     *
     * @throws AppException
     */
    public function testAccountNoticePageHandlerOverPage(): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $request = new Request(['REQUEST_URI' => '/notices/10'], [], [AccountInterface::AUTH_TOKEN => $token]);
        $response = $this->app->handle($request);

        self::assertEquals(Response::NOT_FOUND, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Page not found/', $response->getBody());
    }
}
