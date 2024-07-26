<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Avatar;

use Exception;

class AvatarException extends Exception
{
    public const INVALID_ID         = 'Incorrect parameter "avatar_id", it required and type int';
    public const INVALID_FLOOR_ID   = 'Incorrect parameter "floor_id", it required and type int';
    public const INVALID_ORIGIN_URL = 'Incorrect parameter "origin_url", it required and type string';
    public const INVALID_SMALL_URL  = 'Incorrect parameter "small_url", it required and type string';
}
