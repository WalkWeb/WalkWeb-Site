<?php

declare(strict_types=1);

namespace App\Domain\Account\MainCharacter\Era;

use Exception;

class EraException extends Exception
{
    public const UNKNOWN_ERA = 'Unknown era';
}
