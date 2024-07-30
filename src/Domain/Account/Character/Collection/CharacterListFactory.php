<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Collection;

use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class CharacterListFactory
{
    use ValidationTrait;

    /**
     * @param array $data
     * @return CharacterListInterface
     * @throws AppException
     */
    public static function create(array $data): CharacterListInterface
    {
        return new CharacterList(
            self::string($data, 'id', CharacterListException::INVALID_ID),
            self::string($data, 'avatar', CharacterListException::INVALID_AVATAR),
            self::string($data, 'profession_name_male', CharacterListException::INVALID_PROFESSION_MALE),
            self::string($data, 'profession_name_female', CharacterListException::INVALID_PROFESSION_FEMALE),
            self::string($data, 'genesis', CharacterListException::INVALID_GENESIS),
            self::int($data, 'floor_id', CharacterListException::INVALID_FLOOR_ID),
            self::int($data, 'level', CharacterListException::INVALID_LEVEL),
        );
    }
}
