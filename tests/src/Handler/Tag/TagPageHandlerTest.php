<?php

declare(strict_types=1);

namespace Test\src\Handler\Tag;

use App\Domain\Post\Tag\TagException;
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
    public function testTagPageHandlerSuccessAll(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/t/path-of-exile/all']);
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
    public function testTagPageHandlerSuccessBest(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/t/path-of-exile/best']);
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
    public function testTagPageHandlerTagNotFound(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/t/not-found/all']);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::NOT_FOUND, $response->getStatusCode());
        self::assertMatchesRegularExpression('/' . TagException::NOT_FOUND . '/', $response->getBody());
    }

    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testTagPageHandlerRatingNotFound(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/t/news/unknown']);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::NOT_FOUND, $response->getStatusCode());
        self::assertMatchesRegularExpression('/' . TagException::UNKNOWN_RATING . '/', $response->getBody());
    }
}
