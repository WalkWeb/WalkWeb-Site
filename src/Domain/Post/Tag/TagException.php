<?php

declare(strict_types=1);

namespace App\Domain\Post\Tag;

use Exception;

class TagException extends Exception
{
    public const INVALID_ID              = 'Incorrect "id" parameter, it required and type string';
    public const INVALID_NAME            = 'Incorrect "name" parameter, it required and type string';
    public const INVALID_SLUG            = 'Incorrect "slug" parameter, it required and type string';
    public const INVALID_ICON            = 'Incorrect "icon" parameter, it required and type string';
    public const INVALID_PREVIEW_POST_ID = 'Incorrect "preview_post_id" parameter, it required and type string';
    public const INVALID_APPROVED        = 'Incorrect "approved" parameter, it required and type bool';
    public const ALREADY_EXIST           = 'TagCollection: tag to be added already exists';
}
