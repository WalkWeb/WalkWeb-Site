<?php

declare(strict_types=1);

namespace App\Domain\Comment;

use App\Domain\Auth\AuthInterface;
use App\Domain\Post\Rating\Rating;
use DateTime;
use Ramsey\Uuid\Uuid;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class CommentFactory
{
    use ValidationTrait;

    /**
     * @param string $postId
     * @param string $message
     * @param AuthInterface|null $user
     * @param string|null $parentId
     * @param int $level
     * @param string $guestName
     * @return Comment
     * @throws AppException
     */
    public static function createNew(
        string $postId,
        string $message,
        ?AuthInterface $user = null,
        ?string $parentId = null,
        int $level = 0,
        string $guestName = ''
    ): Comment
    {
        if ($user === null && $guestName === '') {
            throw new AppException(CommentException::NO_USER_AND_GUEST_NAME);
        }

        return new Comment(
            Uuid::uuid4()->toString(),
            $postId,
            $user ? $user->getId() : null,
            $user ? $user->getName() : $guestName,
            $user ? $user->getAvatar() : Comment::DEFAULT_AVATAR,
            $user ? $user->getLevel()->getLevel() : 0,
            $message,
            false,
            $parentId,
            $level,
            new Rating(0, 0, 0),
            new DateTime(),
            new DateTime(),
        );
    }
}
