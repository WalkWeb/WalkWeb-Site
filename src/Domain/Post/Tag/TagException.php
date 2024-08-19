<?php

declare(strict_types=1);

namespace App\Domain\Post\Tag;

use Exception;

class TagException extends Exception
{
    public const EXPECTED_ARRAY          = 'TagCollectionFactory: expected array data';
    public const NOT_FOUND               = 'Тег не найден';
    public const UNKNOWN_RATING          = 'Указан неизвестный рейтинг';

    public const INVALID_ID              = 'Incorrect "id" parameter, it required and type string';
    public const INVALID_NAME            = 'Incorrect "name" parameter, it required and type string';
    public const INVALID_NAME_LENGTH     = 'Incorrect "name", should be min-max length: ';
    public const INVALID_SLUG            = 'Incorrect "slug" parameter, it required and type string';
    public const INVALID_SLUG_LENGTH     = 'Incorrect "slug", should be min-max length: ';
    public const INVALID_ICON            = 'Incorrect "icon" parameter, it required and type string';
    public const INVALID_ICON_LENGTH     = 'Incorrect "icon", should be min-max length: ';
    public const INVALID_PREVIEW_POST_ID = 'Incorrect "preview_post_id" parameter, it required and type string or null';
    public const INVALID_APPROVED        = 'Incorrect "approved" parameter, it required and type int';
    public const ALREADY_EXIST           = 'TagCollection: tag to be added already exists';
}
