<?php

declare(strict_types=1);

namespace App\Domain\Account\Carma;

use App\Domain\Account\Character\Season\Season;
use Ramsey\Uuid\Uuid;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class CarmaFactory
{
    use ValidationTrait;

    /**
     * @param array $data
     * @return CarmaInterface
     * @throws AppException
     */
    public static function create(array $data): CarmaInterface
    {
        return new Carma(
            self::uuid($data, 'id', CarmaException::INVALID_ID),
            self::uuid($data, 'account_id', CarmaException::INVALID_ACCOUNT_ID),
            new Season(self::int($data, 'season_id', CarmaException::INVALID_SEASON_ID)),
            self::int($data, 'carma', CarmaException::INVALID_CARMA),
            self::int($data, 'uses', CarmaException::INVALID_USES),
        );
    }

    /**
     * @param string $accountId
     * @return CarmaInterface
     * @throws AppException
     */
    public static function createNew(string $accountId): CarmaInterface
    {
        if (!Uuid::isValid($accountId)) {
            throw new AppException(CarmaException::INVALID_NEW);
        }

        return new Carma(
            Uuid::uuid4()->toString(),
            $accountId,
            new Season(ACTIVE_SEASON),
            0,
            0,
        );
    }
}
