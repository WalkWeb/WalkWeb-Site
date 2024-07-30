<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Collection;

use Exception;

class CharacterListException extends Exception
{
    public const ALREADY_EXIST             = 'CharacterCollection: character to be added already exists';
    public const EXPECTED_ARRAY            = 'CharacterCollectionFactory: expected array data';

    public const INVALID_ID                = 'Incorrect parameter "id", it required and type string';
    public const INVALID_AVATAR            = 'Incorrect parameter "avatar", it required and type string';
    public const INVALID_PROFESSION_MALE   = 'Incorrect parameter "profession_name_male", it required and type string';
    public const INVALID_PROFESSION_FEMALE = 'Incorrect parameter "profession_name_female", it required and type string';
    public const INVALID_GENESIS           = 'Incorrect parameter "genesis", it required and type string';
    public const INVALID_FLOOR_ID          = 'Incorrect parameter "floor_id", it required and type int';
    public const INVALID_LEVEL             = 'Incorrect parameter "level", it required and type int';
}
