<?php

declare(strict_types=1);

namespace App\Domain\Post\Tag;

use Exception;

class TagException extends Exception
{
    public const string EXPECTED_ARRAY          = 'TagCollectionFactory: expected array data';
    public const string NOT_FOUND               = 'Тег не найден';
    public const string UNKNOWN_RATING          = 'Указан неизвестный рейтинг';

    public const string INVALID_ID              = 'Incorrect "id" parameter, it required and type string';
    public const string INVALID_NAME            = 'Incorrect "name" parameter, it required and type string';
    public const string INVALID_NAME_LENGTH     = 'Incorrect "name", should be min-max length: ';
    public const string INVALID_SLUG            = 'Incorrect "slug" parameter, it required and type string';
    public const string INVALID_SLUG_LENGTH     = 'Incorrect "slug", should be min-max length: ';
    public const string INVALID_ICON            = 'Incorrect "icon" parameter, it required and type string';
    public const string INVALID_ICON_LENGTH     = 'Incorrect "icon", should be min-max length: ';
    public const string INVALID_PREVIEW_POST_ID = 'Incorrect "preview_post_id" parameter, it required and type string or null';
    public const string INVALID_APPROVED        = 'Incorrect "approved" parameter, it required and type int';
    public const string ALREADY_EXIST           = 'TagCollection: tag to be added already exists';
}
