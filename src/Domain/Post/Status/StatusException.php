<?php

declare(strict_types=1);

namespace App\Domain\Post\Status;

use Exception;

class StatusException extends Exception
{
    public const UNKNOWN_POST_STATUS_ID = 'Unknown post status id';
}
