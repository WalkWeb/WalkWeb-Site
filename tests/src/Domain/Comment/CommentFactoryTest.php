<?php

declare(strict_types=1);

namespace Test\src\Domain\Comment;

use App\Domain\Comment\CommentException;
use App\Domain\Comment\CommentFactory;
use App\Domain\Post\Rating\Rating;
use DateTime;
use Exception;
use Ramsey\Uuid\Uuid;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class CommentFactoryTest extends AbstractTest
{
    /**
     * @dataProvider createSuccessDataProvider
     * @param array $data
     * @throws Exception
     */
    public function testCommentFactoryCreateSuccess(array $data): void
    {
        $comment = CommentFactory::create($data);

        self::assertEquals($data['id'], $comment->getId());
        self::assertEquals($data['post_id'], $comment->getPostId());
        self::assertEquals($data['message'], $comment->getMessage());
        self::assertEquals((bool)$data['approved'], $comment->isApproved());
        self::assertEquals($data['parent_id'], $comment->getParentId());
        self::assertEquals($data['level'], $comment->getLevel());
        self::assertEquals($data['likes'], $comment->getRating()->getLikes());
        self::assertEquals($data['dislikes'], $comment->getRating()->getDislikes());
        self::assertEquals($data['user_reaction'], $comment->getRating()->getUserReaction());
        self::assertEquals($data['is_liked'], $comment->isLiked());

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
     * @dataProvider createFailDataProvider
     * @param array $data
     * @param string $error
     */
    public function testCommentFactoryCreateFail(array $data, string $error): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage($error);
        CommentFactory::create($data);
    }

    /**
     * @dataProvider createNewFromUserDataProvider
     * @param string $postId
     * @param string $message
     * @param string|null $parentId
     * @param int $level
     * @throws AppException
     */
    public function testCommentFactoryCreateNewFromUser(
        string $postId,
        string $message,
        ?string $parentId,
        int $level
    ): void
    {
        $user = $this->createUser();

        $comment = CommentFactory::createNew($postId, $message, $user, $parentId, $level);

        self::assertTrue(Uuid::isValid($comment->getId()));
        self::assertEquals($postId, $comment->getPostId());
        self::assertEquals($user->getId(), $comment->getAuthorId());
        self::assertEquals($user->getName(), $comment->getAuthorName());
        self::assertEquals($user->getAvatar(), $comment->getAuthorAvatar());
        self::assertEquals($user->getLevel()->getLevel(), $comment->getAuthorLevel());
        self::assertEquals($message, $comment->getMessage());
        self::assertFalse($comment->isApproved());
        self::assertEquals($parentId, $comment->getParentId());
        self::assertEquals($level, $comment->getLevel());
        self::assertEquals(new Rating(0, 0, 0), $comment->getRating());
        self::assertTrue((new DateTime())->diff($comment->getCreatedAt())->s <= 1);
        self::assertTrue((new DateTime())->diff($comment->getUpdatedAt())->s <= 1);
    }

    /**
     * @dataProvider createNewFromGuestDataProvider
     * @param string $postId
     * @param string $guestName
     * @param string $message
     * @param string|null $parentId
     * @param int $level
     * @throws AppException
     */
    public function testCommentFactoryCreateNewFromGuest(
        string $postId,
        string $guestName,
        string $message,
        ?string $parentId,
        int $level
    ): void
    {
        $comment = CommentFactory::createNew($postId, $message, null, $parentId, $level, $guestName);

        self::assertTrue(Uuid::isValid($comment->getId()));
        self::assertEquals($postId, $comment->getPostId());
        self::assertEquals(null, $comment->getAuthorId());
        self::assertEquals($guestName, $comment->getAuthorName());
        self::assertEquals(DEFAULT_AVATAR, $comment->getAuthorAvatar());
        self::assertEquals(0, $comment->getAuthorLevel());
        self::assertEquals($message, $comment->getMessage());
        self::assertFalse($comment->isApproved());
        self::assertEquals($parentId, $comment->getParentId());
        self::assertEquals($level, $comment->getLevel());
        self::assertEquals(new Rating(0, 0, 0), $comment->getRating());
        self::assertTrue((new DateTime())->diff($comment->getCreatedAt())->s <= 1);
        self::assertTrue((new DateTime())->diff($comment->getUpdatedAt())->s <= 1);
    }

    public function testCommentFactoryCreateNewFail(): void
    {
        $this->expectException(AppException::class);
        $this->expectExceptionMessage(CommentException::NO_USER_AND_GUEST_NAME);
        CommentFactory::createNew('6546f23d-e03d-4557-b990-c87d0a17d620', 'message');
    }

    /**
     * @return array
     */
    public function createSuccessDataProvider(): array
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
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => false,
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
                    'user_reaction' => 1,
                    'author_name'   => 'NameModerator',
                    'author_avatar' => '/img/avatars/it/designer/female/01.jpg',
                    'author_level'  => 4,
                    'is_liked'      => true,
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
                    'user_reaction' => -1,
                    'author_name'   => null,
                    'author_avatar' => null,
                    'author_level'  => null,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-18 16:00:00',
                    'updated_at'    => '2024-06-18 16:00:00',
                ],
            ],
        ];
    }

    /**
     * @return array
     */
    public function createFailDataProvider(): array
    {
        return [
            // miss id
            [
                [
                    'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                    'author_id'     => '1e3a3b27-12da-4c73-a3a7-b83092705b01',
                    'guest_name'    => '',
                    'message'       => 'comment 1',
                    'approved'      => 1,
                    'parent_id'     => null,
                    'level'         => 0,
                    'likes'         => 0,
                    'dislikes'      => 0,
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_ID,
            ],
            // id invalid type
            [
                [
                    'id'            => 123,
                    'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                    'author_id'     => '1e3a3b27-12da-4c73-a3a7-b83092705b01',
                    'guest_name'    => '',
                    'message'       => 'comment 1',
                    'approved'      => 1,
                    'parent_id'     => null,
                    'level'         => 0,
                    'likes'         => 0,
                    'dislikes'      => 0,
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_ID,
            ],
            // id invalid uuid
            [
                [
                    'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433401xxx',
                    'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                    'author_id'     => '1e3a3b27-12da-4c73-a3a7-b83092705b01',
                    'guest_name'    => '',
                    'message'       => 'comment 1',
                    'approved'      => 1,
                    'parent_id'     => null,
                    'level'         => 0,
                    'likes'         => 0,
                    'dislikes'      => 0,
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_ID,
            ],
            // miss post_id
            [
                [
                    'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433401',
                    'author_id'     => '1e3a3b27-12da-4c73-a3a7-b83092705b01',
                    'guest_name'    => '',
                    'message'       => 'comment 1',
                    'approved'      => 1,
                    'parent_id'     => null,
                    'level'         => 0,
                    'likes'         => 0,
                    'dislikes'      => 0,
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_POST_ID,
            ],
            // post_id invalid type
            [
                [
                    'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433401',
                    'post_id'       => null,
                    'author_id'     => '1e3a3b27-12da-4c73-a3a7-b83092705b01',
                    'guest_name'    => '',
                    'message'       => 'comment 1',
                    'approved'      => 1,
                    'parent_id'     => null,
                    'level'         => 0,
                    'likes'         => 0,
                    'dislikes'      => 0,
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_POST_ID,
            ],
            // post_id invalid uuid
            [
                [
                    'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433401',
                    'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02xxx',
                    'author_id'     => '1e3a3b27-12da-4c73-a3a7-b83092705b01',
                    'guest_name'    => '',
                    'message'       => 'comment 1',
                    'approved'      => 1,
                    'parent_id'     => null,
                    'level'         => 0,
                    'likes'         => 0,
                    'dislikes'      => 0,
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_POST_ID,
            ],
            // miss author_id
            [
                [
                    'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433401',
                    'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                    'guest_name'    => '',
                    'message'       => 'comment 1',
                    'approved'      => 1,
                    'parent_id'     => null,
                    'level'         => 0,
                    'likes'         => 0,
                    'dislikes'      => 0,
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_AUTHOR_ID,
            ],
            // author_id invalid type
            [
                [
                    'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433401',
                    'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                    'author_id'     => [],
                    'guest_name'    => '',
                    'message'       => 'comment 1',
                    'approved'      => 1,
                    'parent_id'     => null,
                    'level'         => 0,
                    'likes'         => 0,
                    'dislikes'      => 0,
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_AUTHOR_ID,
            ],
            // author_id invalid uuid
            [
                [
                    'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433401',
                    'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                    'author_id'     => '1e3a3b27-12da-4c73-a3a7-b83092705b01xxx',
                    'guest_name'    => '',
                    'message'       => 'comment 1',
                    'approved'      => 1,
                    'parent_id'     => null,
                    'level'         => 0,
                    'likes'         => 0,
                    'dislikes'      => 0,
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_AUTHOR_ID,
            ],
            // miss guest_name
            [
                [
                    'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433401',
                    'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                    'author_id'     => null,
                    'message'       => 'comment 1',
                    'approved'      => 1,
                    'parent_id'     => null,
                    'level'         => 0,
                    'likes'         => 0,
                    'dislikes'      => 0,
                    'user_reaction' => 0,
                    'author_name'   => null,
                    'author_avatar' => null,
                    'author_level'  => null,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_GUEST_NAME,
            ],
            // guest_name invalid type
            [
                [
                    'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433401',
                    'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                    'author_id'     => null,
                    'guest_name'    => 2.4,
                    'message'       => 'comment 1',
                    'approved'      => 1,
                    'parent_id'     => null,
                    'level'         => 0,
                    'likes'         => 0,
                    'dislikes'      => 0,
                    'user_reaction' => 0,
                    'author_name'   => null,
                    'author_avatar' => null,
                    'author_level'  => null,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_GUEST_NAME,
            ],
            // miss message
            [
                [
                    'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433401',
                    'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                    'author_id'     => '1e3a3b27-12da-4c73-a3a7-b83092705b01',
                    'guest_name'    => '',
                    'approved'      => 1,
                    'parent_id'     => null,
                    'level'         => 0,
                    'likes'         => 0,
                    'dislikes'      => 0,
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_MESSAGE,
            ],
            // message invalid type
            [
                [
                    'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433401',
                    'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                    'author_id'     => '1e3a3b27-12da-4c73-a3a7-b83092705b01',
                    'guest_name'    => '',
                    'message'       => true,
                    'approved'      => 1,
                    'parent_id'     => null,
                    'level'         => 0,
                    'likes'         => 0,
                    'dislikes'      => 0,
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_MESSAGE,
            ],
            // miss approved
            [
                [
                    'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433401',
                    'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                    'author_id'     => '1e3a3b27-12da-4c73-a3a7-b83092705b01',
                    'guest_name'    => '',
                    'message'       => 'comment 1',
                    'parent_id'     => null,
                    'level'         => 0,
                    'likes'         => 0,
                    'dislikes'      => 0,
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_APPROVED,
            ],
            // approved invalid type
            [
                [
                    'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433401',
                    'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                    'author_id'     => '1e3a3b27-12da-4c73-a3a7-b83092705b01',
                    'guest_name'    => '',
                    'message'       => 'comment 1',
                    'approved'      => true,
                    'parent_id'     => null,
                    'level'         => 0,
                    'likes'         => 0,
                    'dislikes'      => 0,
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_APPROVED,
            ],
            // miss parent_id
            [
                [
                    'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433401',
                    'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                    'author_id'     => '1e3a3b27-12da-4c73-a3a7-b83092705b01',
                    'guest_name'    => '',
                    'message'       => 'comment 1',
                    'approved'      => 1,
                    'level'         => 0,
                    'likes'         => 0,
                    'dislikes'      => 0,
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_PARENT_ID,
            ],
            // parent_id invalid type
            [
                [
                    'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433401',
                    'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                    'author_id'     => '1e3a3b27-12da-4c73-a3a7-b83092705b01',
                    'guest_name'    => '',
                    'message'       => 'comment 1',
                    'approved'      => 1,
                    'parent_id'     => 230,
                    'level'         => 0,
                    'likes'         => 0,
                    'dislikes'      => 0,
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_PARENT_ID,
            ],
            // miss level
            [
                [
                    'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433401',
                    'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                    'author_id'     => '1e3a3b27-12da-4c73-a3a7-b83092705b01',
                    'guest_name'    => '',
                    'message'       => 'comment 1',
                    'approved'      => 1,
                    'parent_id'     => null,
                    'likes'         => 0,
                    'dislikes'      => 0,
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_LEVEL,
            ],
            // level invalid type
            [
                [
                    'id'            => '7d78bc1d-9919-4c56-bc89-f4bd2e433401',
                    'post_id'       => '7684ad22-613b-4c65-9bad-b7dfdd394c02',
                    'author_id'     => '1e3a3b27-12da-4c73-a3a7-b83092705b01',
                    'guest_name'    => '',
                    'message'       => 'comment 1',
                    'approved'      => 1,
                    'parent_id'     => null,
                    'level'         => '0',
                    'likes'         => 0,
                    'dislikes'      => 0,
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_LEVEL,
            ],
            // miss author_name
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
                    'user_reaction' => 0,
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_AUTHOR_NAME,
            ],
            // author_name invalid type
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
                    'user_reaction' => 0,
                    'author_name'   => 1000,
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_AUTHOR_NAME,
            ],
            // miss author_avatar
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
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_AUTHOR_AVATAR,
            ],
            // author_avatar invalid type
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
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => true,
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_AUTHOR_AVATAR,
            ],
            // miss author_level
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
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_AUTHOR_LEVEL,
            ],
            // author_level invalid type
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
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => '1',
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_AUTHOR_LEVEL,
            ],
            // miss created_at
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
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_CREATED_AT,
            ],
            // created_at invalid type
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
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => true,
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_CREATED_AT,
            ],
            // created_at invalid date
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
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-99-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_CREATED_AT,
            ],
            // miss updated_at
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
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_UPDATED_AT,
            ],
            // updated_at invalid type
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
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => false,
                ],
                CommentException::INVALID_UPDATED_AT,
            ],
            // updated_at invalid date
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
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => true,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-99 16:00:00',
                ],
                CommentException::INVALID_UPDATED_AT,
            ],
            // miss is_liked
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
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_IS_LIKED,
            ],
            // is_liked invalid type
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
                    'user_reaction' => 0,
                    'author_name'   => 'DemoUser',
                    'author_avatar' => '/img/avatars/it/analyst/male/01.jpg',
                    'author_level'  => 1,
                    'is_liked'      => 1,
                    'created_at'    => '2024-06-16 16:00:00',
                    'updated_at'    => '2024-06-16 16:00:00',
                ],
                CommentException::INVALID_IS_LIKED,
            ],
        ];
    }

    /**
     * @return array
     */
    public function createNewFromUserDataProvider(): array
    {
        return [
            [
                '1b9d64b2-e2cf-482b-83d4-11777c67fef7',
                'comment message-1',
                null,
                0,
            ],
            [
                '18f0baa2-2256-4812-835b-f0c37e6cf86f',
                'comment message-2',
                'feef4417-720e-45fd-b3b6-28627b4aae80',
                3,
            ],
        ];
    }

    /**
     * @return array
     */
    public function createNewFromGuestDataProvider(): array
    {
        return [
            [
                '1b9d64b2-e2cf-482b-83d4-11777c67fef7',
                'guest name',
                'comment message-1',
                null,
                0,
            ],
            [
                '48ce9c57-cf9f-4feb-93dc-48d45ff49646',
                'guest name 2',
                'comment message-2',
                '57dda030-287e-418a-a50c-b22142ab92fd',
                2,
            ],
        ];
    }
}
