<?php

declare(strict_types=1);

namespace Test\src\Handler\Post;

use App\Domain\Account\AccountInterface;
use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class CreatePostPageHandlerTest extends AbstractTest
{
    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testCreatePostPageHandlerSuccess(string $template): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $request = new Request(['REQUEST_URI' => '/post/create/default'], [], [AccountInterface::AUTH_TOKEN => $token]);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Создание нового поста/', $response->getBody());
    }

    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testCreatePostPageHandlerCommunitySuccess(string $template): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $request = new Request(['REQUEST_URI' => '/post/create/diablo-2-wiki'], [], [AccountInterface::AUTH_TOKEN => $token]);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Создание нового поста/', $response->getBody());
        self::assertMatchesRegularExpression('/Diablo 2: База знаний/', $response->getBody());
    }

    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testCreatePostPageHandlerCommunityNotFound(string $template): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $request = new Request(['REQUEST_URI' => '/post/create/unknown-community'], [], [AccountInterface::AUTH_TOKEN => $token]);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::NOT_FOUND, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Вы пытаетесь создать пост для несуществующего сообщества/', $response->getBody());
    }

    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testCreatePostPageHandlerNoAuth(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/post/create/default']);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Вход/', $response->getBody());
    }
}
