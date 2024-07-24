<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Genesis;

use Exception;

class GenesisException extends Exception
{
    public const INVALID_ID       = 'Incorrect parameter "genesis_id", it required and type int';
    public const INVALID_THEME_ID = 'Incorrect parameter "theme_id", it required and type int';
    public const INVALID_ICON     = 'Incorrect parameter "genesis_icon", it required and type string';
    public const INVALID_PLURAL   = 'Incorrect parameter "genesis_plural", it required and type string';
    public const INVALID_SINGLE   = 'Incorrect parameter "genesis_single", it required and type string';
}
