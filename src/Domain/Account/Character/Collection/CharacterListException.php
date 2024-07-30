<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Collection;

use Exception;

class CharacterListException extends Exception
{
    public const INVALID_ID         = 'Incorrect parameter "id", it required and type string';
    public const INVALID_AVATAR     = 'Incorrect parameter "avatar", it required and type string';
    public const INVALID_PROFESSION = 'Incorrect parameter "profession", it required and type string';
    public const INVALID_GENESIS    = 'Incorrect parameter "genesis", it required and type string';
    public const INVALID_LEVEL      = 'Incorrect parameter "level", it required and type int';
}
