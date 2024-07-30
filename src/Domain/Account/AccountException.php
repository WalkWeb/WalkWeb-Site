<?php

declare(strict_types=1);

namespace App\Domain\Account;

use Exception;

class AccountException extends Exception
{
    public const NOT_FOUND                     = 'User not found';
    public const LOGIN_ALREADY_EXIST           = 'User with this login already exists';
    public const NAME_ALREADY_EXIST            = 'User with this name already exists';
    public const EMAIL_ALREADY_EXIST           = 'User with this email already exists';
    public const INVALID_LOGIN_OR_PASSWORD     = 'Invalid login or password';
    public const MISS_MAIN_CHARACTER           = 'Miss main character';
    public const ALREADY_EXIST                 = 'AccountCollection: account to be added already exists';
    public const EXPECTED_ARRAY                = 'AccountCollectionFactory: expected array data';
    public const UNKNOWN_THEME_ID              = 'Unknown theme id';

    // id
    public const INVALID_ID                    = 'Incorrect parameter "id", it required and type string (uuid)';
    // level
    public const INVALID_LEVEL                 = 'Incorrect parameter "level", it required and type int';
    // exp
    public const INVALID_EXP                   = 'Incorrect parameter "exp", it required and type int';
    // carma
    public const INVALID_CARMA                 = 'Incorrect parameter "carma", it required and type int';
    // login
    public const INVALID_LOGIN                 = 'Incorrect parameter "login", it required and type string';
    public const INVALID_LOGIN_LENGTH          = 'Incorrect parameter "login", should be min-max length: ';
    public const INVALID_LOGIN_SYMBOL          = 'Incorrect "login", symbol, excepted letters, numbers, hyphen or underscore';
    // name
    public const INVALID_NAME                  = 'Incorrect parameter "name", it required and type string';
    public const INVALID_NAME_LENGTH           = 'Incorrect parameter "name", should be min-max length: ';
    public const INVALID_NAME_SYMBOL           = 'Incorrect "name", symbol, excepted letters, numbers, hyphen or underscore';
    // avatar
    public const INVALID_AVATAR                = 'Incorrect parameter "avatar", it required and type string';
    public const INVALID_AVATAR_LENGTH         = 'Incorrect parameter "avatar", should be min-max length: ';
    // password
    public const INVALID_PASSWORD              = 'Incorrect parameter "password", it required and type string';
    public const INVALID_PASSWORD_LENGTH       = 'Incorrect parameter "password", should be min-max length: ';
    // email
    public const INVALID_EMAIL                 = 'Incorrect parameter "email", it required and type string';
    public const INVALID_EMAIL_LENGTH          = 'Incorrect parameter "email", should be min-max length: ';
    public const INVALID_EMAIL_SYMBOL          = 'Incorrect email';
    // email_verified
    public const INVALID_EMAIL_VERIFIED        = 'Incorrect parameter "email_verified", excepted int (0 or 1)';
    // reg_complete
    public const INVALID_REG_COMPLETE          = 'Incorrect parameter "reg_complete", excepted int (0 or 1)';
    // auth_token
    public const INVALID_AUTH_TOKEN            = 'Incorrect parameter "auth_token", it required and type string';
    public const INVALID_AUTH_TOKEN_LENGTH     = 'Incorrect parameter "auth_token", should be min-max length: ';
    // verified_token
    public const INVALID_VERIFIED_TOKEN        = 'Incorrect parameter "verified_token", it required and type string';
    public const INVALID_VERIFIED_TOKEN_LENGTH = 'Incorrect parameter "verified_token", should be min-max length: ';
    // template
    public const INVALID_TEMPLATE              = 'Incorrect parameter "template", it required and type string';
    public const INVALID_TEMPLATE_LENGTH       = 'Incorrect parameter "template", should be min-max length: ';
    // ip
    public const INVALID_IP                    = 'Incorrect parameter "ip", it required and type string';
    public const INVALID_IP_LENGTH             = 'Incorrect parameter "ip", should be min-max length: ';
    // ref
    public const INVALID_REF                   = 'Incorrect parameter "ref", it required and type string'; // TODO rename to referral
    public const INVALID_REF_LENGTH            = 'Incorrect parameter "ref", should be min-max length: ';
    // ref
    public const INVALID_USER_AGENT            = 'Incorrect parameter "user_agent", it required and type string';
    public const INVALID_USER_AGENT_LENGTH     = 'Incorrect parameter "user_agent", should be min-max length: ';
    // can_like
    public const INVALID_CAN_LIKE              = 'Incorrect parameter "can_like", excepted int (0 or 1)';
    // floor_id
    public const INVALID_FLOOR_ID              = 'Incorrect parameter "floor_id", excepted int';
    public const UNKNOWN_FLOOR_ID              = 'Unknown account floor id';
    // status_id
    public const INVALID_STATUS_ID             = 'Incorrect parameter "status_id", excepted int';
    public const UNKNOWN_STATUS_ID             = 'Unknown account status id';
    // group_id
    public const INVALID_GROUP_ID              = 'Incorrect parameter "group_id", excepted int';
    public const UNKNOWN_GROUP_ID              = 'Unknown account group id';
    // upload
    public const INVALID_UPLOAD                = 'Incorrect parameter "upload", excepted int';
    public const INVALID_UPLOAD_VALUE          = 'Incorrect parameter "upload", should be min-max value: ';
    // main_character
    public const INVALID_MAIN_CHARACTER        = 'Incorrect parameter "main_character", excepted array or skip';
    // created_at
    public const INVALID_CREATED_AT            = 'Incorrect parameter "created_at", it required and type string (date format)';
    // updated_at
    public const INVALID_UPDATED_AT            = 'Incorrect parameter "created_at", it required and type string (date format)';
    // redirect_url
    public const INVALID_REDIRECT_URL          = 'Incorrect parameter "redirect_url", it required and type string';
    public const INVALID_REDIRECT_URL_LENGTH   = 'Incorrect parameter "redirect_url", should be min-max length: ';

    // for create request
    // floor_id
    public const INVALID_REQUEST_FLOOR_ID       = 'Incorrect parameter "floor_id", excepted string';
    // genesis_id
    public const INVALID_GENESIS_ID             = 'Incorrect parameter "genesis_id", excepted string';
    // profession_id
    public const INVALID_PROFESSION_ID          = 'Incorrect parameter "profession_id", excepted string';
    // avatar_id
    public const INVALID_AVATAR_ID              = 'Incorrect parameter "avatar_id", excepted string';
}
