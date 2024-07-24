<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Profession;

use Exception;

class ProfessionException extends Exception
{
    public const INVALID_ID          = 'Incorrect parameter "profession_id", it required and type int';
    public const INVALID_ICON        = 'Incorrect parameter "profession_icon", it required and type string';
    public const INVALID_NAME_MALE   = 'Incorrect parameter "profession_name_male", it required and type string';
    public const INVALID_NAME_FEMALE = 'Incorrect parameter "profession_name_female", it required and type string';
}
