<?php

declare(strict_types=1);

namespace App\Domain\Account\Status;

use App\Domain\Account\AccountException;
use WalkWeb\NW\AppException;

class AccountStatus implements AccountStatusInterface
{
    private static array $map = [
        self::ACTIVE  => 'Active',
        self::BLOCKED => 'Blocked',
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
            throw new AppException(AccountException::UNKNOWN_STATUS_ID . ': ' . $id);
        }

        $this->name = self::$map[$id];
    }
}
