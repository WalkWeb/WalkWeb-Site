<?php

declare(strict_types=1);

namespace App\Domain\Account\Character;

use Exception;

class CharacterException extends Exception
{
    public const UNKNOWN_SEASON_ID = 'Unknown season id';
}
