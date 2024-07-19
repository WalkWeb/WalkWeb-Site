<?php

declare(strict_types=1);

namespace Test\src\Handler\Account;

use App\Domain\Account\AccountInterface;
use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class VerifiedEmailPageHandlerTest extends AbstractTest
{
    private const URI = '/verified/email';

    /**
     * Тест на успешное отображение страницы о необходимости подтвердить email-адрес
     *
     * @throws AppException
     */
    public function testNotVerifiedEmailHandlerSuccess(): void
    {
        $authToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a3';
        $request = new Request(['REQUEST_URI' => self::URI], [], [AccountInterface::AUTH_TOKEN => $authToken]);
        $response = $this->createApp()->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Подтвердите ваш email/', $response->getBody());
        self::assertMatchesRegularExpression('/Вам необходимо подтвердить email чтобы активировать аккаунт/', $response->getBody());
    }

    /**
     * Тест на ситуацию, когда неавторизованный пользователь пытается открыть страницу - ему сообщается, нужно
     * авторизоваться
     *
     * @throws AppException
     */
    public function testNotVerifiedEmailHandlerUnauthorized(): void
    {
        $request = new Request(['REQUEST_URI' => self::URI]);
        $response = $this->createApp()->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/form/', $response->getBody());
        self::assertMatchesRegularExpression("/\/verified\/email/", $response->getBody());
    }

    /**
     * Тест на ситуацию, когда пользователь с подтвержденной почтой пытается открыть страницу - ему сообщается что все ок
     *
     * @throws AppException
     */
    public function testNotVerifiedEmailHandlerAlreadyVerified(): void
    {
        $authToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $request = new Request(['REQUEST_URI' => self::URI], [], [AccountInterface::AUTH_TOKEN => $authToken]);
        $response = $this->createApp()->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Email успешно подтвержден/', $response->getBody());
        self::assertMatchesRegularExpression('/Вы успешно подтвердили email, ваш аккаунт активирован и все возможности доступны/', $response->getBody());
    }
}
