<?php

declare(strict_types=1);

namespace App\Domain\Account\DTO;

use App\Domain\Account\AccountException;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class CreateAccountRequestFactory
{
    use ValidationTrait;

    /**
     * @param array $data
     * @return CreateAccountRequest
     * @throws AppException
     */
    public static function create(array $data): CreateAccountRequest
    {
        return new CreateAccountRequest(
            self::string($data, 'login', AccountException::INVALID_LOGIN),
            self::string($data, 'email', AccountException::INVALID_EMAIL),
            self::string($data, 'password', AccountException::INVALID_PASSWORD),
            self::int($data, 'floor_id', AccountException::INVALID_FLOOR_ID),
            self::int($data, 'genesis_id', AccountException::INVALID_GENESIS_ID),
            self::int($data, 'profession_id', AccountException::INVALID_PROFESSION_ID),
            self::int($data, 'avatar_id', AccountException::INVALID_AVATAR_ID),
            self::string($data, 'ref', AccountException::INVALID_REF),
            self::string($data, 'user_agent', AccountException::INVALID_USER_AGENT),
            self::string($data, 'ip', AccountException::INVALID_IP),
        );
    }
}
