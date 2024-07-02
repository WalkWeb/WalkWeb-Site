<?php

declare(strict_types=1);

namespace App\Domain\Account;

use Exception;

class AccountException extends Exception
{
    public const UNKNOWN_STATUS_ID = 'Unknown account status id';
    public const UNKNOWN_GROUP_ID  = 'Unknown account group id';
    public const UNKNOWN_FLOOR_ID  = 'Unknown account floor id';
}
