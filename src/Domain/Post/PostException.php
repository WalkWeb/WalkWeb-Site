<?php

declare(strict_types=1);

namespace App\Domain\Post;

use Exception;

class PostException extends Exception
{
    public const INVALID_ID                  = 'Incorrect "id" parameter, it required and type string';
    public const INVALID_TITLE               = 'Incorrect "title" parameter, it required and type string';
    public const INVALID_TITLE_VALUE         = 'Incorrect "title", should be min-max length: ';
    public const INVALID_SLUG                = 'Incorrect "slug" parameter, it required and type string';
    public const INVALID_CONTENT             = 'Incorrect "content" parameter, it required and type string';
    public const INVALID_CONTENT_LENGTH      = 'Incorrect "content", should be min-max length: ';
    public const INVALID_HTML_CONTENT        = 'Incorrect "html_content" parameter, it required and type string';
    public const INVALID_HTML_CONTENT_LENGTH = 'Incorrect "html_content", should be min-max length: ';
    public const INVALID_STATUS_ID           = 'Incorrect "status_id" parameter, it required and type int';
    public const INVALID_COMMENTS_COUNT      = 'Incorrect "comments_count" parameter, it required and type int';
    public const INVALID_PUBLISHED           = 'Incorrect "published" parameter, it required and type int';
    public const INVALID_TAGS                = 'Incorrect "tags" parameter, it required and type array';
    public const INVALID_IS_LIKED_DATA       = 'Incorrect "is_liked" data, expected arrays';
    public const INVALID_CREATED_AT          = 'Incorrect "created_at" parameter, it required and type string date';
    public const INVALID_UPDATED_AT          = 'Incorrect "updated_at" parameter, expected string date or empty';
}
