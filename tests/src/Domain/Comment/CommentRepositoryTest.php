<?php

declare(strict_types=1);

namespace Test\src\Domain\Comment;

use App\Domain\Comment\CommentException;
use App\Domain\Comment\CommentRepository;
use DateTime;
use Exception;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class CommentRepositoryTest extends AbstractTest
{
    /**
     * @dataProvider getSuccessDataProvider
     * @param array $data
     * @throws Exception
     */
    public function testCommentRepositoryGetSuccess(array $data): void
    {
        $comment = $this->getRepository()->get($data['id']);

        self::assertEquals($data['id'], $comment->getId());
        self::assertEquals($data['post_id'], $comment->getPostId());
        self::assertEquals($data['message'], $comment->getMessage());
        self::assertEquals((bool)$data['approved'], $comment->isApproved());
        self::assertEquals($data['parent_id'], $comment->getParentId());
        self::assertEquals($data['level'], $comment->getLevel());
        self::assertEquals($data['likes'], $comment->getRating()->getLikes());
        self::assertEquals($data['dislikes'], $comment->getRating()->getDislikes());

        if ($data['author_id'] !== null) {
            self::assertEquals($data['author_id'], $comment->getAuthorId());
            self::assertEquals($data['author_name'], $comment->getAuthorName());
            self::assertEquals($data['author_avatar'], $comment->getAuthorAvatar());
            self::assertEquals($data['author_level'], $comment->getAuthorLevel());
        } else {
            self::assertNull($comment->getAuthorId());
            self::assertEquals($data['guest_name'], $comment->getAuthorName());
            self::assertEquals(DEFAULT_AVATAR, $comment->getAuthorAvatar());
            self::assertEquals(0, $comment->getAuthorLevel());
        }

        self::assertEquals(new DateTime($data['created_at']), $comment->getCreatedAt());
        self::assertEquals(new DateTime($data['updated_at']), $comment->getUpdatedAt());
    }

    /**
     * @throws AppException
     */
    public function testCommentRepositoryGetNotFound(): void
    {
        self::assertNull($this->getRepository()->get('93003592-16e7-4cef-93ca-d127ae78a5ab'));
    }

    /**
     * @throws AppException
     */
    public function testCommentRepositoryGetCollectionNoAuth(): void
    {
        $comments = $this->getRepository()->getByPost('7684ad22-613b-4c65-9bad-b7dfdd394c02');

        self::assertCount(3, $comments);
    }

    /**
     * @throws AppException
     */
    public function testCommentRepositoryGetCollectionAuth(): void
    {
        $user = $this->getUser('VBajfT8P6PFtrkHhCqb7ZNwIFG45a1');
        $comments = $this->getRepository()->getByPost('7684ad22-613b-4c65-9bad-b7dfdd394c02', $user);

        self::assertCount(3, $comments);
    }

    /**
     * @dataProvider getAuthorIdDataProvider
     * @param string $commentId
     * @param string|null $expectedAuthorId
     * @throws AppException
     */
    public function testCommentRepositoryGetAuthorIdSuccess(string $commentId, ?string $expectedAuthorId): void
    {
        self::assertEquals($expectedAuthorId, $this->getRepository()->getAuthorId($commentId));
    }

    public function testCommentRepositoryGetAuthorIdNotFound(): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage(CommentException::GET_AUTHOR_ERROR);
        $this->getRepository()->getAuthorId('52f24c49-63c2-4901-a472-cbab04127654');
    }

    /**
     * @return array
     */
    public function getSuccessDataProvider(): array
    {
        return [
            [
                [
                    'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433401',
                    'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                    'author_id'     => '1e3a3b27-12da-4c73-a3a7-b83092705b01',
                    'guest_name'    => '',
                    'message'       => 'comment 1',
                    'approved'      => 1,
                    'parent_id'     => null,
                    'level'         => 0,
                    'likes'         => 0,
                    'dislikes'      => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
            ],
            [
                [
                    'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433402',
                    'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                    'author_id'     => '1e3a3b27-12da-4c73-a3a7-b83092705b04',
                    'guest_name'    => '',
                    'message'       => 'comment 2',
                    'approved'      => 1,
                    'parent_id'     => null,
                    'level'         => 0,
                    'likes'         => 3,
                    'dislikes'      => 1,
                    'author_name'   => 'NameModerator',
                    'author_avatar' => '/img/avatars/it/designer/female/01.jpg',
                    'author_level'  => 4,
                    'created_at'    => '2024-06-17 16:00:00',
                    'updated_at'    => '2024-06-17 16:00:00',
                ],
            ],
            [
                [
                    'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433403',
                    'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                    'author_id'     => null,
                    'guest_name'    => 'guest name',
                    'message'       => 'comment 3',
                    'approved'      => 1,
                    'parent_id'     => null,
                    'level'         => 0,
                    'likes'         => 0,
                    'dislikes'      => 1,
                    'author_name'   => null,
                    'author_avatar' => null,
                    'author_level'  => null,
                    'created_at'    => '2024-06-18 16:00:00',
                    'updated_at'    => '2024-06-18 16:00:00',
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function getAuthorIdDataProvider(): array
    {
        return [
            [
                '7d78bc1d-9919-4c56-bc89-f4bd2e433401',
                '1e3a3b27-12da-4c73-a3a7-b83092705b01',
            ],
            [
                '7d78bc1d-9919-4c56-bc89-f4bd2e433402',
                '1e3a3b27-12da-4c73-a3a7-b83092705b04',
            ],
            [
                '7d78bc1d-9919-4c56-bc89-f4bd2e433403',
                null,
            ],
        ];
    }

    /**
     * @return CommentRepository
     * @throws AppException
     */
    private function getRepository(): CommentRepository
    {
        return new CommentRepository(self::getContainer());
    }
}
