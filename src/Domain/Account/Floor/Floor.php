<?php

declare(strict_types=1);

namespace App\Domain\Account\Floor;

use App\Domain\Account\AccountException;

class Floor implements FloorInterface
{
    private static array $map = [
        self::MALE   => 'Male',
        self::FEMALE => 'Female',
    ];

    private int $id;

    private string $name;

    /**
     * @param int $id
     * @throws AccountException
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
     * @throws AccountException
     */
    private function setName(int $id): void
    {
        if (!array_key_exists($id, self::$map)) {
            throw new AccountException(AccountException::UNKNOWN_FLOOR_ID . ': ' . $id);
        }

        $this->name = self::$map[$id];
    }
}
