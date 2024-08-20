<?php

declare(strict_types=1);

namespace App\Domain\Account\Collection;

use App\Domain\Account\AccountException;
use App\Domain\Account\AccountInterface;
use App\Domain\Account\Group\AccountGroup;
use App\Domain\Account\Status\AccountStatus;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class AccountListFactory
{
    use ValidationTrait;

    /**
     * @param array $data
     * @return AccountListInterface
     * @throws AppException
     */
    public static function create(array $data): AccountListInterface
    {
        return new AccountList(
            self::uuid($data, 'id', AccountException::INVALID_ID),
            self::string($data, 'avatar', AccountException::INVALID_AVATAR),
            self::nameValidation($data),
            self::int($data, 'level', AccountException::INVALID_LEVEL),
            self::int($data, 'exp', AccountException::INVALID_EXP),
            new AccountStatus(self::int($data, 'status_id', AccountException::INVALID_STATUS_ID)),
            new AccountGroup(self::int($data, 'group_id', AccountException::INVALID_GROUP_ID)),
            self::int($data, 'carma', AccountException::INVALID_CARMA),
        );
    }

    /**
     * @param array $data
     * @return string
     * @throws AppException
     */
    private static function nameValidation(array $data): string
    {
        $name = self::string($data, 'name', AccountException::INVALID_NAME);

        self::stringMinMaxLength(
            $name,
            AccountInterface::NAME_MIN_LENGTH,
            AccountInterface::NAME_MAX_LENGTH,
            AccountException::INVALID_NAME_LENGTH . AccountInterface::NAME_MIN_LENGTH . '-' . AccountInterface::NAME_MAX_LENGTH
        );

        self::parent($name, AccountInterface::NAME_PARENT, AccountException::INVALID_NAME_SYMBOL);

        return $name;
    }
}
