<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Profession;

use App\Domain\Account\Character\Genesis\GenesisFactory;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class ProfessionFactory
{
    use ValidationTrait;

    /**
     * @param array $data
     * @return ProfessionInterface
     * @throws AppException
     */
    public static function create(array $data): ProfessionInterface
    {
        return new Profession(
            self::int($data, 'profession_id', ProfessionException::INVALID_ID),
            GenesisFactory::create($data),
            self::string($data, 'profession_icon', ProfessionException::INVALID_ICON),
            self::string($data, 'profession_name_male', ProfessionException::INVALID_NAME_MALE),
            self::string($data, 'profession_name_female', ProfessionException::INVALID_NAME_FEMALE),
        );
    }
}
