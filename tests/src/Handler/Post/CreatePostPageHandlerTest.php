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
     * TODO add default view
     *
     * @throws AppException
     */
    public function testCreatePostPageHandlerSuccess(): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $request = new Request(['REQUEST_URI' => '/post/create'], [], [AccountInterface::AUTH_TOKEN => $token]);
        $response = $this->createApp('inferno')->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Создание нового поста/', $response->getBody());
    }

    /**
     * TODO add default view
     *
     * @throws AppException
     */
    public function testCreatePostPageHandlerNoAuth(): void
    {
        $request = new Request(['REQUEST_URI' => '/post/create']);
        $response = $this->createApp('inferno')->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Вход/', $response->getBody());
    }
}
