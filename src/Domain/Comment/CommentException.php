<?php

declare(strict_types=1);

namespace App\Domain\Comment;

use Exception;

class CommentException extends Exception
{
    public const NO_USER_AND_GUEST_NAME = 'No user or guest name';

    public const INVALID_ID            = 'Incorrect "id" parameter, it required and type string (uuid)';
    public const INVALID_POST_ID       = 'Incorrect "post_id" parameter, it required and type string (uuid)';
    public const INVALID_AUTHOR_ID     = 'Incorrect "author_id" parameter, it required and type string (uuid) or null';
    public const INVALID_AUTHOR_NAME   = 'Incorrect "author_name" parameter, it required and type string or null';
    public const INVALID_GUEST_NAME    = 'Incorrect "guest_name" parameter, it required and type string';
    public const INVALID_AUTHOR_AVATAR = 'Incorrect "author_avatar" parameter, it required and type string or null';
    public const INVALID_AUTHOR_LEVEL  = 'Incorrect "author_level" parameter, it required and type int';
    public const INVALID_MESSAGE       = 'Incorrect "message" parameter, it required and type string';
    public const INVALID_APPROVED      = 'Incorrect "approved" parameter, it required and type int';
    public const INVALID_PARENT_ID     = 'Incorrect "parent_id" parameter, it required and type string (uuid) or null';
    public const INVALID_LEVEL         = 'Incorrect "level" parameter, it required and type int';
    public const INVALID_CREATED_AT    = 'Incorrect "created_at" parameter, it required and type string date';
    public const INVALID_UPDATED_AT    = 'Incorrect "updated_at" parameter, it required and type string date';
}
