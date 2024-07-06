<?php

declare(strict_types=1);

namespace App\Domain\Account\MainCharacter;

use App\Domain\Account\MainCharacter\Era\EraInterface;
use App\Domain\Account\MainCharacter\Level\LevelInterface;

interface MainCharacterInterface
{
    // TODO min-max value

    public function getId(): string;
    public function getAccountId(): string;
    public function getEra(): EraInterface;
    public function getLevel(): LevelInterface;
    public function getEnergyBonus(): int;
    public function getUploadBonus(): int;
}
