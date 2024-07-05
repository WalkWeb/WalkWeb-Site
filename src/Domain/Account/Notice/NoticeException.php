<?php

declare(strict_types=1);

namespace App\Domain\Account\Notice;

use Exception;

class NoticeException extends Exception
{
    public const NOT_FOUND          = 'Notice not found';
    public const UNKNOWN_TYPE       = 'Unknown type notice';
    public const INVALID_ID         = 'Incorrect "id" parameter, it required and type string';
    public const INVALID_TYPE       = 'Incorrect "type" parameter, it required and type int';
    public const INVALID_ACCOUNT_ID = 'Incorrect "account_id" parameter, it required and type string';
    public const INVALID_MESSAGE    = 'Incorrect "message" parameter, it required and type string';
    public const INVALID_VIEW       = 'Incorrect "view" parameter, it required and type int';
    public const INVALID_CREATED_AT = 'Incorrect "created_at" parameter, it required and type string date format';
    public const ALREADY_EXIST      = 'NoticeException: notice to be added already exists';
}
