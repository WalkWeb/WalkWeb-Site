<?php

declare(strict_types=1);

namespace App\Domain\Rating\DTO\Genesis;

use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class GenesisRatingFactory
{
    use ValidationTrait;

    /**
     * @param array $data
     * @return GenesisRatingInterface
     * @throws AppException
     */
    public static function create(array $data): GenesisRatingInterface
    {
        return new GenesisRating(
            self::int($data, 'id', GenesisRatingException::INVALID_ID),
            self::string($data, 'icon', GenesisRatingException::INVALID_ICON),
            self::string($data, 'name', GenesisRatingException::INVALID_NAME),
            (int)self::intOrNull($data, 'member_count', GenesisRatingException::INVALID_MEMBER_COUNT),
            (int)self::intOrNull($data, 'post_count', GenesisRatingException::INVALID_POST_COUNT),
            (int)self::intOrNull($data, 'comment_count', GenesisRatingException::INVALID_COMMENT_COUNT),
            (int)self::intOrNull($data, 'carma_count', GenesisRatingException::INVALID_CARMA_COUNT),
        );
    }
}
