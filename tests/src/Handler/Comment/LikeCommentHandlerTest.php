<?php

declare(strict_types=1);

namespace Test\src\Handler\Comment;

use App\Domain\Account\AccountInterface;
use App\Domain\Comment\CommentException;
use App\Domain\Comment\CommentInterface;
use App\Domain\Comment\CommentRepository;
use Test\AbstractTest;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Request;
use WalkWeb\NW\Response;

class LikeCommentHandlerTest extends AbstractTest
{
    /**
     * @throws AppException
     */
    public function testLikeCommentHandlerSuccess(): void
    {
        $authToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a3';
        $accountId = '1e3a3b27-12da-4c73-a3a7-b83092705b03';
        $commendId = '7d78bc1d-9919-4c56-bc89-f4bd2e433401';

        // Проверяем изначальный рейтинг
        $comment = $this->getComment($commendId);

        self::assertEquals(0, $comment->getRating()->getLikes());
        self::assertEquals(0, $comment->getRating()->getDislikes());

        // Отправляем запрос на лайк
        $request = new Request(
            ['REQUEST_URI' => '/comment/like/' . $commendId, 'REQUEST_METHOD' => 'POST'],
            [],
            [AccountInterface::AUTH_TOKEN => $authToken]
        );
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertEquals(self::jsonEncode(['success' => true]), $response->getBody());

        // Проверяем обновленный рейтинг
        $comment = $this->getComment($commendId);

        self::assertEquals(1, $comment->getRating()->getLikes());
        self::assertEquals(0, $comment->getRating()->getDislikes());

        // Проверяем запись в таблице lk_account_like_comment
        $data = $this->getLikeData($commendId, $accountId);

        self::assertEquals($accountId, $data['account_id']);
        self::assertEquals($commendId, $data['comment_id']);
        self::assertEquals(1, $data['value']);
    }

    /**
     * @throws AppException
     */
    public function testLikeCommentHandlerUnauthorized(): void
    {
        $commendId = '7d78bc1d-9919-4c56-bc89-f4bd2e433401';

        // Проверяем изначальный рейтинг
        $comment = $this->getComment($commendId);

        self::assertEquals(0, $comment->getRating()->getLikes());
        self::assertEquals(0, $comment->getRating()->getDislikes());

        $request = new Request(['REQUEST_URI' => '/comment/like/' . $commendId, 'REQUEST_METHOD' => 'POST']);
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError(CommentException::ERROR_NO_AUTH, $response);

        // Проверяем, что рейтинг не изменился
        $comment = $this->getComment($commendId);

        self::assertEquals(0, $comment->getRating()->getLikes());
        self::assertEquals(0, $comment->getRating()->getDislikes());

    }

    /**
     * @throws AppException
     */
    public function testLikeCommentHandlerOwner(): void
    {
        $authToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $commendId = '7d78bc1d-9919-4c56-bc89-f4bd2e433401';

        // Проверяем изначальный рейтинг
        $comment = $this->getComment($commendId);

        self::assertEquals(0, $comment->getRating()->getLikes());
        self::assertEquals(0, $comment->getRating()->getDislikes());

        $request = new Request(
            ['REQUEST_URI' => '/comment/like/' . $commendId, 'REQUEST_METHOD' => 'POST'],
            [],
            [AccountInterface::AUTH_TOKEN => $authToken]
        );
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError(CommentException::ERROR_OWNER, $response);

        // Проверяем, что рейтинг не изменился
        $comment = $this->getComment($commendId);

        self::assertEquals(0, $comment->getRating()->getLikes());
        self::assertEquals(0, $comment->getRating()->getDislikes());
    }

    /**
     * @throws AppException
     */
    public function testLikeCommentHandlerAlreadyLiked(): void
    {
        $authToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a1';
        $commendId = '7d78bc1d-9919-4c56-bc89-f4bd2e433402';

        // Проверяем изначальный рейтинг
        $comment = $this->getComment($commendId);

        self::assertEquals(3, $comment->getRating()->getLikes());
        self::assertEquals(1, $comment->getRating()->getDislikes());

        $request = new Request(
            ['REQUEST_URI' => '/comment/like/' . $commendId, 'REQUEST_METHOD' => 'POST'],
            [],
            [AccountInterface::AUTH_TOKEN => $authToken]
        );
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError(CommentException::ERROR_ALREADY_LIKE, $response);

        // Проверяем, что рейтинг не изменился
        $comment = $this->getComment($commendId);

        self::assertEquals(3, $comment->getRating()->getLikes());
        self::assertEquals(1, $comment->getRating()->getDislikes());
    }

    /**
     * @throws AppException
     */
    public function testLikeCommentHandlerDontCanLike(): void
    {
        $authToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a0';
        $commendId = '7d78bc1d-9919-4c56-bc89-f4bd2e433401';

        // Проверяем изначальный рейтинг
        $comment = $this->getComment($commendId);

        self::assertEquals(0, $comment->getRating()->getLikes());
        self::assertEquals(0, $comment->getRating()->getDislikes());

        $request = new Request(
            ['REQUEST_URI' => '/comment/like/' . $commendId, 'REQUEST_METHOD' => 'POST'],
            [],
            [AccountInterface::AUTH_TOKEN => $authToken]
        );
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError(CommentException::ERROR_DONT_LIKE, $response);

        // Проверяем, что рейтинг не изменился
        $comment = $this->getComment($commendId);

        self::assertEquals(0, $comment->getRating()->getLikes());
        self::assertEquals(0, $comment->getRating()->getDislikes());
    }

    /**
     * @throws AppException
     */
    public function testLikeCommentHandlerInvalidId(): void
    {
        $authToken = 'VBajfT8P6PFtrkHhCqb7ZNwIFG45a3';
        $commendId = '7d78bc1d-9919-4c56-bc89-f4bd2e433xxx';

        $request = new Request(
            ['REQUEST_URI' => '/comment/like/' . $commendId, 'REQUEST_METHOD' => 'POST'],
            [],
            [AccountInterface::AUTH_TOKEN => $authToken]
        );
        $response = $this->app->handle($request);

        self::assertEquals(Response::OK, $response->getStatusCode());
        self::assertJsonError(CommentException::INVALID_ID, $response);
    }

    /**
     * @param string $commendId
     * @return CommentInterface
     * @throws AppException
     */
    private function getComment(string $commendId): CommentInterface
    {
        return (new CommentRepository(self::getContainer()))->get($commendId);
    }

    /**
     * @param string $commentId
     * @param string $accountId
     * @return array
     * @throws AppException
     */
    private function getLikeData(string $commentId, string $accountId): array
    {
        return self::getContainer()->getConnectionPool()->getConnection()->query(
            'SELECT * FROM `lk_account_like_comment` WHERE `comment_id` = ? AND `account_id` = ?',
            [
                ['type' => 's', 'value' => $commentId],
                ['type' => 's', 'value' => $accountId],
            ],
            true
        );
    }
}
