<?php

declare(strict_types=1);

namespace Test\src\Handler\Post;

use App\Domain\Account\AccountInterface;
use App\Handler\Post\CreatePostHandler;
use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class CreatePostHandlerTest extends AbstractTest
{
    /**
     * @throws AppException
     */
    public function testCreatePostHandlerSuccess(): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $request = new Request([
            'REQUEST_URI' => '/post/create', 'REQUEST_METHOD' => 'POST'],
            [
                'title'   => 'Title',
                'content' => '[p]text text text[/p]',
            ],
            [AccountInterface::AUTH_TOKEN => $token]
        );
        $response = $this->createApp()->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertTrue(self::jsonDecode($response->getBody())['success']);
        self::assertIsString(self::jsonDecode($response->getBody())['slug']);
    }

    /**
     * @dataProvider failDataProvider
     * @param array $data
     * @param string $error
     * @throws AppException
     */
    public function testCreatePostHandlerFail(array $data, string $error): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $request = new Request([
            'REQUEST_URI' => '/post/create', 'REQUEST_METHOD' => 'POST'],
            $data,
            [AccountInterface::AUTH_TOKEN => $token]
        );
        $response = $this->createApp()->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError($error, $response);
    }

    /**
     * @throws AppException
     */
    public function testCreatePostHandlerNoAuth(): void
    {
        $request = new Request([
            'REQUEST_URI' => '/post/create', 'REQUEST_METHOD' => 'POST'],
            [
                'title'   => 'Title',
                'content' => '[p]text text text[/p]',
            ],
        );
        $response = $this->createApp()->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError(CreatePostHandler::NO_AUTH, $response);
    }

    /**
     * @return array
     */
    public function failDataProvider(): array
    {
        return [
            // title over min length
            [
                [
                    'title'   => 't',
                    'content' => 'content',
                ],
                'Incorrect "title", should be min-max length: 2-80',
            ],
            // miss content
            [
                [
                    'title'   => 'title',
                ],
                'Incorrect "content" parameter, it required and type string',
            ],
        ];
    }
}
