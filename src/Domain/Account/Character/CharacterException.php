<?php

declare(strict_types=1);

namespace App\Domain\Account\Character;

use Exception;

class CharacterException extends Exception
{
    public const UNKNOWN_SEASON_ID = 'Unknown season id';

    public const INVALID_ID                = 'Incorrect parameter "character_id", it required and type string (uuid)';
    public const INVALID_ACCOUNT_ID        = 'Incorrect parameter "account_id", it required and type string (uuid)';
    public const INVALID_MAIN_CHARACTER_ID = 'Incorrect parameter "main_character_id", it required and type string (uuid)';
    public const INVALID_AVATAR            = 'Incorrect parameter "avatar", it required and type string';
    public const INVALID_SEASON_ID         = 'Incorrect parameter "season_id", it required and type int';
    public const INVALID_FLOOR_ID          = 'Incorrect parameter "floor_id", it required and type int';
}
