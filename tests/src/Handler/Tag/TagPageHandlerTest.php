<?php

declare(strict_types=1);

namespace Test\src\Handler\Tag;

use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class TagPageHandlerTest extends AbstractTest
{
    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testTagPageHandlerSuccess(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/t/path-of-exile']);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Просмотр постов по тегу/', $response->getBody());
        self::assertMatchesRegularExpression('/PATH OF EXILE/', $response->getBody());
        self::assertMatchesRegularExpression('/title post 2/', $response->getBody());
    }

    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testTagPageHandlerNotFound(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/t/not-found']);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::NOT_FOUND, $response->getStatusCode());
        self::assertMatchesRegularExpression('/Тег не найден/', $response->getBody());
    }
}
