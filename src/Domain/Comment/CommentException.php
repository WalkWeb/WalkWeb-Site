<?php

declare(strict_types=1);

namespace App\Domain\Comment;

use Exception;

class CommentException extends Exception
{
    public const NO_USER_AND_GUEST_NAME = 'No user or guest name';
}
