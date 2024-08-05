<?php

declare(strict_types=1);

namespace App\Domain\Post\Rating;

use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class RatingFactory
{
    use ValidationTrait;

    /**
     * Создает объект Rating на основе массива параметров
     *
     * @param array $data
     * @return RatingInterface
     * @throws AppException
     */
    public static function create(array $data): RatingInterface
    {
        return new Rating(
            self::int($data, 'likes', RatingException::INVALID_LIKES),
            self::int($data, 'dislikes', RatingException::INVALID_DISLIKES),
            self::int($data, 'user_reaction', RatingException::INVALID_USER_REACTION)
        );
    }
}
