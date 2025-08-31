<?php

declare(strict_types=1);

namespace App\Domain\Auth;

use Exception;

class AuthException extends Exception
{
    public const string INVALID_ID                     = 'Incorrect "id" parameter, it required and type string';
    public const string INVALID_NAME                   = 'Incorrect "name" parameter, it required and type string';
    public const string INVALID_AVATAR                 = 'Incorrect "avatar" parameter, it required and type string';
    public const string INVALID_MAIN_CHARACTER_ID      = 'Incorrect "main_character_id" parameter, it required and type string (uuid)';
    public const string INVALID_ACCOUNT_GROUP_ID       = 'Incorrect "account_group_id" parameter, it required and type int';
    public const string INVALID_ACCOUNT_STATUS_ID      = 'Incorrect "account_status_id" parameter, it required and type int';
    public const string INVALID_ENERGY_DATA            = 'Incorrect "energy" parameter, it required and type array';
    public const string INVALID_CAN_LIKE               = 'Incorrect "can_like" parameter, it required and type int';
    public const string INVALID_LEVEL                  = 'Incorrect "level" data, excepted array';
    public const string INVALID_TEMPLATE               = 'Incorrect "template" data, excepted string';
    public const string INVALID_EMAIL_VERIFIED         = 'Incorrect "email_verified" parameter, it required and type int';
    public const string INVALID_UPLOAD_BONUS           = 'Incorrect "upload_bonus" parameter, it required and type int';
    public const string INVALID_UPLOAD                 = 'Incorrect parameter "upload", excepted int';
    public const string INVALID_UPLOAD_VALUE           = 'Incorrect parameter "upload", should be min-max value: ';
    public const string INVALID_VERIFIED_TOKEN         = 'Incorrect parameter "verified_token", it required and type string';
    public const string INVALID_VERIFIED_TOKEN_LENGTH  = 'Incorrect parameter "verified_token", should be min-max length: ';
}
