<?php

declare(strict_types=1);

namespace Test\src\Domain\Comment;

use App\Domain\Comment\CommentException;
use App\Domain\Comment\CommentFactory;
use App\Domain\Comment\CommentInterface;
use App\Domain\Post\Rating\Rating;
use DateTime;
use Ramsey\Uuid\Uuid;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class CommentFactoryTest extends AbstractTest
{
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
        self::assertEquals(CommentInterface::DEFAULT_AVATAR, $comment->getAuthorAvatar());
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
