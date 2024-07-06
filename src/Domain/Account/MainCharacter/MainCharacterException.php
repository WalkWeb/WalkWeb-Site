<?php

declare(strict_types=1);

namespace App\Domain\Account\MainCharacter;

use Exception;

class MainCharacterException extends Exception
{
    // id
    public const INVALID_ID               = 'Incorrect parameter "character_id", it required and type string';
    public const INVALID_ID_VALUE         = 'Incorrect parameter "character_id", excepted uuid';

    // account_id
    public const INVALID_ACCOUNT_ID       = 'Incorrect parameter "account_id", it required and type string';
    public const INVALID_ACCOUNT_ID_VALUE = 'Incorrect parameter "account_id", excepted uuid';

    // era_id
    public const INVALID_ERA_ID           = 'Incorrect parameter "era_id", it required and type int';

    // energy_bonus
    public const INVALID_ENERGY_BONUS     = 'Incorrect parameter "energy_bonus", it required and type int';

    // upload_bonus
    public const INVALID_UPLOAD_BONUS     = 'Incorrect parameter "upload_bonus", it required and type int';
}
