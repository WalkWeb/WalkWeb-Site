<?php

declare(strict_types=1);

namespace Test\src\Handler\Post;

use App\Domain\Account\AccountInterface;
use App\Domain\Post\PostException;
use App\Domain\Post\PostInterface;
use App\Handler\Post\CreatePostHandler;
use Exception;
use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class CreatePostHandlerTest extends AbstractTest
{
    private const URI = '/post/create/default';

    /**
     * @dataProvider successDataProvider
     * @param string $communitySlug
     * @param string|null $communityId
     * @throws AppException
     */
    public function testCreatePostHandlerSuccess(string $communitySlug, ?string $communityId): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a4';
        $user = $this->getUser($token);

        // Проверка изначального опыта
        self::assertEquals(450, $user->getLevel()->getExp());

        // Проверка изначального количества постов
        self::assertEquals(0, $this->getUserData($token)['post_count']);

        $request = new Request([
            'REQUEST_URI' => '/post/create/' . $communitySlug, 'REQUEST_METHOD' => 'POST'],
            [
                'title'   => 'Title',
                'content' => '[p]text text text[/p]',
                'tags'    => ['tag-1', 'tag-2'],
            ],
            [AccountInterface::AUTH_TOKEN => $token]
        );
        $response = $this->app->handle($request);

        $responseData = self::jsonDecode($response->getBody());

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertTrue($responseData['success']);
        self::assertIsString($responseData['slug']);

        // Проверка того, что опыт увеличился
        $user = $this->getUser($token);
        self::assertEquals(450 + PostInterface::CREATE_EXP, $user->getLevel()->getExp());

        // Проверка увеличения количества постов
        self::assertEquals(1, $this->getUserData($token)['post_count']);

        self::assertEquals($communityId, $this->getData($responseData['slug'])['community_id']);
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
            'REQUEST_URI' => self::URI, 'REQUEST_METHOD' => 'POST'],
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
            'REQUEST_URI' => self::URI, 'REQUEST_METHOD' => 'POST'],
            [
                'title'   => 'Title',
                'content' => '[p]text text text[/p]',
                'tags'    => [],
            ],
        );
        $response = $this->createApp()->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError(CreatePostHandler::NO_AUTH, $response);
    }

    /**
     * @throws Exception
     */
    public function testCreatePostHandlerNoEnergy(): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a8';
        $request = new Request([
            'REQUEST_URI' => self::URI, 'REQUEST_METHOD' => 'POST'],
            [
                'title'   => 'Title',
                'content' => '[p]text text text[/p]',
                'tags'    => [],
            ],
            [AccountInterface::AUTH_TOKEN => $token]
        );

        $this->app->handle($request);
        $this->app->handle($request);
        $this->app->handle($request);
        $this->app->handle($request);
        $this->app->handle($request);
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError(sprintf(PostException::NO_CREATE_ENERGY, 30, 0), $response);
    }

    public function successDataProvider(): array
    {
        return [
            [
                PostInterface::NO_COMMUNITY,
                null,
            ],
            [
                'path-of-exile-wiki',
                '19b2d329-4ca0-4c07-8fb5-18a3a3e80005',
            ],
        ];
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
                    'tags'    => [],
                ],
                'Incorrect "title", should be min-max length: 2-80',
            ],
            // miss content
            [
                [
                    'title'   => 'title',
                    'tags'    => [],
                ],
                'Incorrect "content" parameter, it required and type string',
            ],
        ];
    }

    /**
     * @param string $slug
     * @return array
     * @throws AppException
     */
    private function getData(string $slug): array
    {
        return self::getContainer()->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `posts` WHERE `slug` = ?',
            [['type' => 's', 'value' => $slug]],
            true
        );
    }
}
