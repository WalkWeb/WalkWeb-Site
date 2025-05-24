<?php

declare(strict_types=1);

namespace App\Domain\Account\MainCharacter;

use App\Domain\Account\MainCharacter\Era\EraInterface;
use App\Domain\Account\MainCharacter\Level\LevelInterface;

class MainCharacter implements MainCharacterInterface
{
    private string $id;
    private string $accountId;
    private EraInterface $era;
    private LevelInterface $level;
    private int $energyBonus;
    private int $uploadBonus;

    public function __construct(
        string $id,
        string $accountId,
        EraInterface $era,
        LevelInterface $level,
        int $energyBonus,
        int $uploadBonus
    ) {
        $this->id = $id;
        $this->accountId = $accountId;
        $this->era = $era;
        $this->level = $level;
        $this->energyBonus = $energyBonus;
        $this->uploadBonus = $uploadBonus;
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getAccountId(): string
    {
        return $this->accountId;
    }

    /**
     * @return EraInterface
     */
    public function getEra(): EraInterface
    {
        return $this->era;
    }

    /**
     * @return LevelInterface
     */
    public function getLevel(): LevelInterface
    {
        return $this->level;
    }

    /**
     * @return int
     */
    public function getEnergyBonus(): int
    {
        return $this->energyBonus;
    }

    /**
     * @return int
     */
    public function getUploadBonus(): int
    {
        return $this->uploadBonus;
    }
}
