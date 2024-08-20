<?php

declare(strict_types=1);

namespace App\Domain\Account\Carma;

use Exception;

class CarmaException extends Exception
{
    public const INVALID_ID         = 'Incorrect parameter "id", it required and type string (uuid)';
    public const INVALID_ACCOUNT_ID = 'Incorrect parameter "account_id", it required and type string (uuid)';
    public const INVALID_SEASON_ID  = 'Incorrect parameter "season_id", it required and type int';
    public const INVALID_CARMA      = 'Incorrect parameter "carma", it required and type int';
    public const INVALID_USES       = 'Incorrect parameter "uses", it required and type int';

    public const INVALID_NEW        = 'CarmaFactory: received invalid $accountId';
}
