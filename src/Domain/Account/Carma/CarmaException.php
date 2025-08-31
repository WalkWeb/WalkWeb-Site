<?php

declare(strict_types=1);

namespace App\Domain\Account\Carma;

use Exception;

class CarmaException extends Exception
{
    public const string INVALID_ID         = 'Incorrect parameter "carma_id", it required and type string (uuid)';
    public const string INVALID_ACCOUNT_ID = 'Incorrect parameter "id", it required and type string (uuid)';
    public const string INVALID_SEASON_ID  = 'Incorrect parameter "season_id", it required and type int';
    public const string INVALID_CARMA      = 'Incorrect parameter "carma", it required and type int';
    public const string INVALID_USES       = 'Incorrect parameter "carma_uses", it required and type int';

    public const string INVALID_NEW        = 'CarmaFactory: received invalid $accountId';
}
