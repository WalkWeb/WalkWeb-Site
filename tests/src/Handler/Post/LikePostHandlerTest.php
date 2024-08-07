<?php

declare(strict_types=1);

namespace Test\src\Handler\Post;

use App\Domain\Account\AccountInterface;
use App\Domain\Post\PostException;
use App\Domain\Post\PostInterface;
use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class LikePostHandlerTest extends AbstractTest
{
    /**
     * @throws AppException
     */
    public function testLikePostHandlerSuccess(): void
    {
        $authToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a3';
        $accountId = '1e3a3b27-12da-4c73-a3a7-b83092705b03';
        $slug = 'slug-post-1-1000';

        // Проверяем изначальный рейтинг
        $data = $this->getPostData($slug);

        self::assertEquals(0, $data['likes']);
        self::assertEquals(0, $data['dislikes']);

        // Отправляем запрос на лайк
        $request = new Request(
            ['REQUEST_URI' => '/post/like/' . $slug, 'REQUEST_METHOD' => 'POST'],
            [],
            [AccountInterface::AUTH_TOKEN => $authToken]
        );
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertEquals(self::jsonEncode(['success' => true]), $response->getBody());

        // Проверяем обновленный рейтинг
        $data = $this->getPostData($slug);

        self::assertEquals(1, $data['likes']);
        self::assertEquals(0, $data['dislikes']);

        // Проверяем запись в таблице lk_account_like_post
        $data = $this->getLikeData($slug, $accountId);
        self::assertEquals($accountId, $data['account_id']);
        self::assertEquals('slug-post-1-1000', $data['post_slug']);
        self::assertEquals(1, $data['value']);
    }

    /**
     * @throws AppException
     */
    public function testLikePostHandlerUnauthorized(): void
    {
        $slug = 'slug-post-1-1000';
        $accountId = '1e3a3b27-12da-4c73-a3a7-b83092705b01';

        // Проверяем изначальный рейтинг
        $data = $this->getPostData($slug);

        self::assertEquals(0, $data['likes']);
        self::assertEquals(0, $data['dislikes']);

        // Отправляем запрос на лайк
        $request = new Request(['REQUEST_URI' => '/post/like/' . $slug, 'REQUEST_METHOD' => 'POST']);
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError(PostException::ERROR_NO_AUTH, $response);

        // Проверяем что рейтинг поста не изменился
        $data = $this->getPostData($slug);

        self::assertEquals(0, $data['likes']);
        self::assertEquals(0, $data['dislikes']);

        // Проверяем, что запись в таблице lk_account_like_post не появилась
        self::assertEquals([], $this->getLikeData($slug, $accountId));
    }

    /**
     * @throws AppException
     */
    public function testLikePostHandlerOwner(): void
    {
        $authToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $accountId = '1e3a3b27-12da-4c73-a3a7-b83092705b01';
        $slug = 'slug-post-1-1000';

        // Проверяем изначальный рейтинг
        $data = $this->getPostData($slug);

        self::assertEquals(0, $data['likes']);
        self::assertEquals(0, $data['dislikes']);

        // Отправляем запрос на лайк
        $request = new Request(
            ['REQUEST_URI' => '/post/like/' . $slug, 'REQUEST_METHOD' => 'POST'],
            [],
            [AccountInterface::AUTH_TOKEN => $authToken]
        );
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError(PostException::ERROR_OWNER, $response);

        // Проверяем что рейтинг поста не изменился
        $data = $this->getPostData($slug);

        self::assertEquals(0, $data['likes']);
        self::assertEquals(0, $data['dislikes']);

        // Проверяем, что запись в таблице lk_account_like_post не появилась
        self::assertEquals([], $this->getLikeData($slug, $accountId));
    }

    /**
     * @throws AppException
     */
    public function testLikePostHandlerAlreadyLiked(): void
    {
        $authToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a4';
        $slug = 'slug-post-1-1000';

        // Проверяем изначальный рейтинг
        $data = $this->getPostData($slug);

        self::assertEquals(0, $data['likes']);
        self::assertEquals(0, $data['dislikes']);

        // Отправляем запрос на лайк
        $request = new Request(
            ['REQUEST_URI' => '/post/like/' . $slug, 'REQUEST_METHOD' => 'POST'],
            [],
            [AccountInterface::AUTH_TOKEN => $authToken]
        );
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError(PostException::ERROR_ALREADY_LIKE, $response);

        // Проверяем что рейтинг поста не изменился
        $data = $this->getPostData($slug);

        self::assertEquals(0, $data['likes']);
        self::assertEquals(0, $data['dislikes']);
    }

    /**
     * @throws AppException
     */
    public function testLikePostHandlerDontCanLike(): void
    {
        $authToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a0';
        $accountId = '1e3a3b27-12da-4c73-a3a7-b83092705b10';
        $slug = 'slug-post-1-1000';

        // Проверяем изначальный рейтинг
        $data = $this->getPostData($slug);

        self::assertEquals(0, $data['likes']);
        self::assertEquals(0, $data['dislikes']);

        // Отправляем запрос на лайк
        $request = new Request(
            ['REQUEST_URI' => '/post/like/' . $slug, 'REQUEST_METHOD' => 'POST'],
            [],
            [AccountInterface::AUTH_TOKEN => $authToken]
        );
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError(PostException::ERROR_DONT_LIKE, $response);

        // Проверяем что рейтинг поста не изменился
        $data = $this->getPostData($slug);

        self::assertEquals(0, $data['likes']);
        self::assertEquals(0, $data['dislikes']);

        // Проверяем, что запись в таблице lk_account_like_post не появилась
        self::assertEquals([], $this->getLikeData($slug, $accountId));
    }

    /**
     * @throws AppException
     */
    public function testLikePostHandlerSlugOverMinLength(): void
    {
        $authToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a3';
        $slug = self::generateString(PostInterface::SLUG_MIN_LENGTH - 1);

        // Отправляем запрос на лайк
        $request = new Request(
            ['REQUEST_URI' => '/post/like/' . $slug, 'REQUEST_METHOD' => 'POST'],
            [],
            [AccountInterface::AUTH_TOKEN => $authToken]
        );
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError(
            PostException::INVALID_SLUG_LENGTH . PostInterface::SLUG_MIN_LENGTH . '-' . PostInterface::SLUG_MAX_LENGTH,
            $response
        );
    }

    /**
     * @throws AppException
     */
    public function testLikePostHandlerSlugOverMaxLength(): void
    {
        $authToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a3';
        $slug = self::generateString(PostInterface::SLUG_MAX_LENGTH + 1);

        // Отправляем запрос на лайк
        $request = new Request(
            ['REQUEST_URI' => '/post/like/' . $slug, 'REQUEST_METHOD' => 'POST'],
            [],
            [AccountInterface::AUTH_TOKEN => $authToken]
        );
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError(
            PostException::INVALID_SLUG_LENGTH . PostInterface::SLUG_MIN_LENGTH . '-' . PostInterface::SLUG_MAX_LENGTH,
            $response
        );
    }

    /**
     * @param string $slug
     * @return array
     * @throws AppException
     */
    private function getPostData(string $slug): array
    {
        return self::getContainer()->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `posts` WHERE `slug` = ?',
            [['type' => 's', 'value' => $slug]],
            true
        );
    }

    /**
     * @param string $slug
     * @param string $accountId
     * @return array
     * @throws AppException
     */
    private function getLikeData(string $slug, string $accountId): array
    {
        return self::getContainer()->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `lk_account_like_post` WHERE `post_slug` = ? AND `account_id` = ?',
            [
                ['type' => 's', 'value' => $slug],
                ['type' => 's', 'value' => $accountId],
            ],
            true
        );
    }
}
