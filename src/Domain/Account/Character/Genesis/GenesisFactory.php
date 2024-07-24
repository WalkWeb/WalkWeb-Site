<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Genesis;

use App\Domain\Theme\Theme;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class GenesisFactory
{
    use ValidationTrait;

    /**
     * @param array $data
     * @return GenesisInterface
     * @throws AppException
     */
    public static function create(array $data): GenesisInterface
    {
        return new Genesis(
            self::int($data, 'genesis_id', GenesisException::INVALID_ID),
            new Theme(self::int($data, 'theme_id', GenesisException::INVALID_THEME_ID)),
            self::string($data, 'genesis_icon', GenesisException::INVALID_ICON),
            self::string($data, 'genesis_plural', GenesisException::INVALID_PLURAL),
            self::string($data, 'genesis_single', GenesisException::INVALID_SINGLE),
        );
    }
}
