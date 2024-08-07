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
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testAccountNoticePageHandlerSuccess(string $template): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $request = new Request(['REQUEST_URI' => '/notices/1'], [], [AccountInterface::AUTH_TOKEN => $token]);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Ваши уведомления/', $response->getBody());
        self::assertMatchesRegularExpression('/notice message 5/', $response->getBody());
    }

    /**
     * Test on print no notices page
     *
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testAccountNoticePageHandlerEmpty(string $template): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a4';
        $request = new Request(['REQUEST_URI' => '/notices/1'], [], [AccountInterface::AUTH_TOKEN => $token]);
        $response = $this->createApp($template)->handle($request);

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

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/form/', $response->getBody());
        self::assertMatchesRegularExpression("/\/notices\/1/", $response->getBody());
    }

    /**
     * Test on get no exist page
     *
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testAccountNoticePageHandlerOverPage(string $template): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $request = new Request(['REQUEST_URI' => '/notices/10'], [], [AccountInterface::AUTH_TOKEN => $token]);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::NOT_FOUND, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Страница не найдена/', $response->getBody());
    }
}
