<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Season;

use App\Domain\Account\Character\CharacterException;
use WalkWeb\NW\AppException;

class Season implements SeasonInterface
{
    private static array $map = [
        self::SEASON_1 => 'Season-1',
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
            throw new AppException(CharacterException::UNKNOWN_SEASON_ID . ': ' . $id);
        }

        $this->name = self::$map[$id];
    }
}
