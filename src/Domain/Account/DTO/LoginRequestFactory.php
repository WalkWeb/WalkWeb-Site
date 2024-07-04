<?php

declare(strict_types=1);

namespace App\Domain\Account\DTO;

use App\Domain\Account\AccountException;
use App\Domain\Account\AccountInterface;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class LoginRequestFactory
{
    use ValidationTrait;

    /**
     * @param array $data
     * @return LoginRequest
     * @throws AppException
     */
    public static function create(array $data): LoginRequest
    {
        return new LoginRequest(
            self::loginValidation($data),
            self::passwordValidate($data),
        );
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    private static function loginValidation(array $data): string
    {
        $login = self::string($data, 'login', AccountException::INVALID_LOGIN);

        self::stringMinMaxLength(
            $login,
            AccountInterface::LOGIN_MIN_LENGTH,
            AccountInterface::LOGIN_MAX_LENGTH,
            AccountException::INVALID_LOGIN_LENGTH . AccountInterface::LOGIN_MIN_LENGTH . '-' . AccountInterface::LOGIN_MAX_LENGTH
        );

        self::parent($login, AccountInterface::LOGIN_PARENT, AccountException::INVALID_LOGIN_SYMBOL);

        return $login;
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    private static function passwordValidate(array $data): string
    {
        $password = self::string($data, 'password', AccountException::INVALID_PASSWORD);

        self::stringMinMaxLength(
            $password,
            AccountInterface::PASSWORD_MIN_LENGTH,
            AccountInterface::PASSWORD_MAX_LENGTH,
            AccountException::INVALID_PASSWORD_LENGTH . AccountInterface::PASSWORD_MIN_LENGTH . '-' . AccountInterface::PASSWORD_MAX_LENGTH
        );

        return $password;
    }
}
