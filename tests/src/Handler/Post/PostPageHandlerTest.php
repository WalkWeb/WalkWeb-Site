<?php

declare(strict_types=1);

namespace Test\src\Handler\Post;

use App\Domain\Account\AccountInterface;
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
    public function testPostPageHandlerUnauthorizedSuccess(string $template): void
    {
        $request = new Request(['REQUEST_URI' => '/p/slug-post-1-1000']);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/title post 1/', $response->getBody());

        // view like icon
        self::assertMatchesRegularExpression('/9650/', $response->getBody());

        // view dislike icon
        self::assertMatchesRegularExpression('/9660/', $response->getBody());
    }

    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testPostPageHandlerAuthorizedSuccess(string $template): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a7';
        $request = new Request(['REQUEST_URI' => '/p/slug-post-1-1000'], [], [AccountInterface::AUTH_TOKEN => $token]);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/title post 1/', $response->getBody());

        // view like icon
        self::assertMatchesRegularExpression('/9650/', $response->getBody());

        // view dislike icon
        self::assertMatchesRegularExpression('/9660/', $response->getBody());
    }

    /**
     * @dataProvider templateDataProvider
     * @param string $template
     * @throws AppException
     */
    public function testPostPageHandlerOwnerSuccess(string $template): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $request = new Request(['REQUEST_URI' => '/p/slug-post-1-1000'], [], [AccountInterface::AUTH_TOKEN => $token]);
        $response = $this->createApp($template)->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertMatchesRegularExpression('/title post 1/', $response->getBody());

        // no view like icon
        self::assertDoesNotMatchRegularExpression('/9650/', $response->getBody());

        // no view dislike icon
        self::assertDoesNotMatchRegularExpression('/9660/', $response->getBody());
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
