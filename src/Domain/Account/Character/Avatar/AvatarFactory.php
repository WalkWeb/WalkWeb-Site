<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Avatar;

use App\Domain\Account\AccountException;
use App\Domain\Account\Character\Genesis\GenesisFactory;
use App\Domain\Account\Floor\Floor;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class AvatarFactory
{
    use ValidationTrait;

    /**
     * @param array $data
     * @return AvatarInterface
     * @throws AppException
     */
    public static function create(array $data): AvatarInterface
    {
        return new Avatar(
            self::int($data, 'avatar_id', AvatarException::INVALID_ID),
            GenesisFactory::create($data),
            new Floor(self::int($data, 'floor_id', AvatarException::INVALID_FLOOR_ID)),
            self::string($data, 'origin_url', AvatarException::INVALID_ORIGIN_URL),
            self::string($data, 'small_url', AvatarException::INVALID_SMALL_URL)
        );
    }
}
