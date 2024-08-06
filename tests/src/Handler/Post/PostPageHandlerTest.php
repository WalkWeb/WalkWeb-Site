<?php

declare(strict_types=1);

namespace Test\src\Handler\Post;

use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class PostPageHandlerTest extends AbstractTest
{
    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testPostPageHandlerSuccess(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/p/slug-post-1-1000']);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/title post 1/', $response->getBody());
    }

    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testPostPageHandlerNotFound(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/p/aaa']);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::NOT_FOUND, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Пост не найден/', $response->getBody());
    }
}
