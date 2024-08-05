<?php

declare(strict_types=1);

namespace App\Domain\Post\Author;

use App\Domain\Account\AccountException;
use App\Domain\Account\Status\AccountStatus;
use WalkWeb\NW\AppException;
use WalkWeb\NW\Traits\ValidationTrait;

class AuthorFactory
{
    use ValidationTrait;

    /**
     * Создает объект Author на основе массива с данными
     *
     * @param array $data
     * @return AuthorInterface
     * @throws AppException
     */
    public static function create(array $data): AuthorInterface
    {
        return new Author(
            self::string($data, 'author_id', AuthorException::INVALID_ID),
            self::string($data, 'author_name', AuthorException::INVALID_NAME),
            self::string($data, 'author_avatar', AuthorException::INVALID_AVATAR),
            self::int($data, 'author_level', AuthorException::INVALID_LEVEL),
            new AccountStatus(self::int($data, 'author_status_id', AuthorException::INVALID_STATUS_ID)),
        );
    }
}
