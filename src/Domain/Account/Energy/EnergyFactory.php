<?php

declare(strict_types=1);

namespace App\Domain\Account\Energy;

use Ramsey\Uuid\Uuid;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class EnergyFactory
{
    use ValidationTrait;

    /**
     * Create object Energy from array (data from database)
     *
     * @param array $data
     * @return EnergyInterface
     * @throws AppException
     */
    public static function create(array $data): EnergyInterface
    {
        self::string($data, 'energy_id', EnergyException::INCORRECT_ENERGY_ID_DATA);
        self::int($data, 'energy', EnergyException::INCORRECT_ENERGY_DATA);
        self::int($data, 'energy_bonus', EnergyException::INCORRECT_ENERGY_BONUS_DATA);
        self::intOrFloat($data, 'energy_updated_at', EnergyException::INCORRECT_UPDATED_AT_DATA);
        self::int($data, 'energy_residue', EnergyException::INCORRECT_RESIDUE_DATA);

        self::stringMinMaxLength(
            $data['energy_id'],
            EnergyInterface::ID_MIN_LENGTH,
            EnergyInterface::ID_MAX_LENGTH,
            EnergyException::INCORRECT_ENERGY_ID_VALUE . EnergyInterface::ID_MIN_LENGTH . '-' . EnergyInterface::ID_MAX_LENGTH
        );

        self::intMinMaxValue(
            $data['energy'],
            EnergyInterface::MIN_ENERGY,
            EnergyInterface::MAX_ENERGY,
            EnergyException::INCORRECT_ENERGY_VALUE . EnergyInterface::MIN_ENERGY . '-' . EnergyInterface::MAX_ENERGY
        );

        self::intMinMaxValue(
            $data['energy_bonus'],
            EnergyInterface::MIN_BONUS,
            EnergyInterface::MAX_BONUS,
            EnergyException::INCORRECT_ENERGY_BONUS_VALUE . EnergyInterface::MIN_BONUS . '-' . EnergyInterface::MAX_BONUS
        );

        return new Energy(
            $data['energy_id'],
            $data['energy'],
            EnergyInterface::BASE_ENERGY + $data['energy_bonus'],
            (float)microtime(true),
            $data['energy_updated_at'],
            $data['energy_residue']
        );
    }

    /**
     * Create new Energy object (for new created user)
     *
     * @return EnergyInterface
     */
    public static function createNew(): EnergyInterface
    {
        return new Energy(
            Uuid::uuid4()->toString(),
            EnergyInterface::BASE_ENERGY,
            EnergyInterface::BASE_ENERGY,
            (float)microtime(true),
            (float)microtime(true),
            0
        );
    }
}
