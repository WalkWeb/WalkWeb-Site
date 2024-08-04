<?php

declare(strict_types=1);

namespace App\Domain\Post\Author;

use Exception;

class AuthorException extends Exception
{
    public const INVALID_ID        = 'Incorrect "author_id" parameter, it required and type string';
    public const INVALID_NAME      = 'Incorrect "author_name" parameter, it required and type string';
    public const INVALID_AVATAR    = 'Incorrect "author_avatar" parameter, it required and type string';
    public const INVALID_LEVEL     = 'Incorrect "author_level" parameter, it required and type string';
    public const INVALID_STATUS_ID = 'Incorrect "author_status_id" parameter, it required and type string';
}
