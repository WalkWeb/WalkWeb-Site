<?php

declare(strict_types=1);

namespace App\Domain\Account\Group;

use App\Domain\Account\AccountException;
use WalkWeb\NW\AppException;

class AccountGroup implements AccountGroupInterface
{
    private static array $map = [
        self::USER       => 'User',
        self::MODERATOR  => 'Moderator',
        self::ADMIN      => 'Admin',
        self::MAIN_ADMIN => 'Main Admin',
    ];

    private int $id;

    private string $name;

    /**
     * @param int $id
     * @throws AppException
     */
    public function __construct(int $id)
    {
        $this->id = $id;
        $this->setName($id);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param int $id
     * @throws AppException
     */
    private function setName(int $id): void
    {
        if (!array_key_exists($id, self::$map)) {
            throw new AppException(AccountException::UNKNOWN_GROUP_ID . ': ' . $id);
        }

        $this->name = self::$map[$id];
    }
}
