<?php

declare(strict_types=1);

namespace Test\src\Handler\Comment;

use App\Domain\Account\AccountInterface;
use App\Domain\Comment\CommentException;
use App\Domain\Comment\CommentInterface;
use App\Domain\Post\PostInterface;
use App\Domain\Post\PostRepository;
use App\Handler\Comment\CreateCommentHandler;
use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class CreateCommentHandlerTest extends AbstractTest
{
    /**
     * @dataProvider successDataProvider
     * @param string $token
     * @param string $postSlug
     * @param int $exp
     * @param string|null $communityId
     * @throws AppException
     */
    public function testCreateCommentHandlerSuccess(string $token, string $postSlug, int $exp, ?string $communityId): void
    {
        $user = $this->getUser($token);

        // Проверка изначального опыта
        self::assertEquals($exp, $user->getLevel()->getExp());

        // Проверка изначального количества комментариев у поста
        self::assertEquals(0, $this->getPost($postSlug)->getCommentsCount());

        // Проверка изначального количества комментариев у пользователя
        self::assertEquals(0, $this->getUserData($token)['comment_count']);

        if ($communityId) {
            // Проверка изначального количества комментариев у сообщества
            self::assertEquals(16932, $this->getCommunityData($communityId)['total_comment_count']);
        }

        $request = new Request([
            'REQUEST_URI' => '/comment/create', 'REQUEST_METHOD' => 'POST'],
            [
                'post_slug' => $postSlug,
                'message'   => 'comment message',
            ],
            [AccountInterface::AUTH_TOKEN => $token]
        );
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());

        self::assertEquals([
            'success'    => true,
            'message'    => 'comment message',
            'avatar'     => '/img/avatars/it/designer/female/01.jpg',
            'name'       => 'NameModerator',
            'level'      => 4,
            'exp_at_lvl' => 35,
            'exp_to_lvl' => 380,
            'exp_width'  => 9,
        ], self::jsonDecode($response->getBody()));

        // Проверка обновленного опыта
        $user = $this->getUser($token);
        self::assertEquals(450 + CommentInterface::CREATE_EXP, $user->getLevel()->getExp());

        // Проверка обновленного количества комментариев у поста
        self::assertEquals(1, $this->getPost($postSlug)->getCommentsCount());

        // Проверка обновленного количества комментариев у пользователя
        self::assertEquals(1, $this->getUserData($token)['comment_count']);

        if ($communityId) {
            // Проверка обновленного количества комментариев у сообщества
            self::assertEquals(16932 + 1, $this->getCommunityData($communityId)['total_comment_count']);
        }
    }

    /**
     * @throws AppException
     */
    public function testCreatePostHandlerNoAuth(): void
    {
        $request = new Request([
            'REQUEST_URI' => '/comment/create', 'REQUEST_METHOD' => 'POST'],
            [
                'post_slug' => 'slug-post-1-1000',
                'message'   => 'comment message',
            ],
        );
        $response = $this->createApp()->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError(CreateCommentHandler::NO_AUTH, $response);
    }

    /**
     * @throws AppException
     */
    public function testCreatePostHandlerError(): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a4';

        $request = new Request([
            'REQUEST_URI' => '/comment/create', 'REQUEST_METHOD' => 'POST'],
            [
                'post_slug' => 'slug-post-1-1000',
                'message'   => 123,
            ],
            [AccountInterface::AUTH_TOKEN => $token]
        );
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError(CommentException::INVALID_MESSAGE, $response);
    }

    /**
     * @throws AppException
     */
    public function testCreatePostHandlerUnknownPost(): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a4';

        $request = new Request([
            'REQUEST_URI' => '/comment/create', 'REQUEST_METHOD' => 'POST'],
            [
                'post_slug' => 'slug-12321-1-1000',
                'message'   => 'comment message',
            ],
            [AccountInterface::AUTH_TOKEN => $token]
        );
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError(CreateCommentHandler::UNKNOWN_POST, $response);
    }

    /**
     * @throws AppException
     */
    public function testCreatePostHandlerNoEnergy(): void
    {
        $token = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a4';

        $request = new Request([
            'REQUEST_URI' => '/comment/create', 'REQUEST_METHOD' => 'POST'],
            [
                'post_slug' => 'slug-post-1-1000',
                'message'   => 'comment message',
            ],
            [AccountInterface::AUTH_TOKEN => $token]
        );

        for ($i = 0; $i < 30; $i++) {
            $this->app->handle($request);
        }

        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError('No energy to create comment. Need 5, have 0', $response);
    }

    public function successDataProvider(): array
    {
        return [
            // no community
            [
                'VBajfT8P6PFtrkHhCqb7ZNwIFG45a4',
                'slug-post-1-1000',
                450,
                null,
            ],
            // exist community
            [
                'VBajfT8P6PFtrkHhCqb7ZNwIFG45a4',
                'slug-post-9-1000',
                450,
                '19b2d329-4ca0-4c07-8fb5-18a3a3e80001',
            ],
        ];
    }

    /**
     * @param string $slug
     * @return PostInterface
     * @throws AppException
     */
    private function getPost(string $slug): PostInterface
    {
        return (new PostRepository(self::getContainer()))->get($slug);
    }

    /**
     * @param string $id
     * @return array
     * @throws AppException
     */
    private function getCommunityData(string $id): array
    {
        return self::getContainer()->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `communities` WHERE `id` = ?',
            [['type' => 's', 'value' => $id]],
            true
        );
    }
}
