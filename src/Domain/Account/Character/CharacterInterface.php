<?php

declare(strict_types=1);

namespace App\Domain\Account\Character;

use App\Domain\Account\Character\Genesis\GenesisInterface;
use App\Domain\Account\Character\Profession\ProfessionInterface;
use App\Domain\Account\Character\Season\SeasonInterface;
use App\Domain\Account\Floor\FloorInterface;
use App\Domain\Account\Character\Level\LevelInterface;

interface CharacterInterface
{
    // TODO getAccountName()

    public function getId(): string;
    public function getAccountId(): string;
    public function getMainCharacterId(): string;
    public function getAvatar(): string;
    public function getSeason(): SeasonInterface;
    public function getGenesis(): GenesisInterface;
    public function getProfession(): ProfessionInterface;
    public function getFloor(): FloorInterface;
    public function getLevel(): LevelInterface;
}
