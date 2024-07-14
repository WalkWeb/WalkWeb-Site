<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use Exception;

class AuthException extends Exception
{
    public const INVALID_ID                     = 'Incorrect "id" parameter, it required and type string';
    public const INVALID_NAME                   = 'Incorrect "name" parameter, it required and type string';
    public const INVALID_AVATAR                 = 'Incorrect "avatar" parameter, it required and type string';
    public const INVALID_ACCOUNT_GROUP_ID       = 'Incorrect "account_group_id" parameter, it required and type int';
    public const INVALID_ACCOUNT_STATUS_ID      = 'Incorrect "account_status_id" parameter, it required and type int';
    public const INVALID_ENERGY_DATA            = 'Incorrect "energy" parameter, it required and type array';
    public const INVALID_CAN_LIKE               = 'Incorrect "can_like" parameter, it required and type int';
    public const INVALID_NOTICES_DATA           = 'Incorrect "notices" parameter, it required and type array';
    public const INVALID_NOTICE_DATA            = 'Incorrect notice data, excepted array';
    public const INVALID_LEVEL                  = 'Incorrect "level" data, excepted array';
    public const INVALID_TEMPLATE               = 'Incorrect "template" data, excepted string';
    public const INVALID_EMAIL_VERIFIED         = 'Incorrect "email_verified" parameter, it required and type int';
    public const INVALID_UPLOAD_BONUS           = 'Incorrect "upload_bonus" parameter, it required and type int';
    public const INVALID_UPLOAD                 = 'Incorrect parameter "upload", excepted int';
    public const INVALID_UPLOAD_VALUE           = 'Incorrect parameter "upload", should be min-max value: ';
}
