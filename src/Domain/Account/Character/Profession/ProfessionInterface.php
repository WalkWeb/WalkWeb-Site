<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Profession;

use App\Domain\Account\Character\Genesis\GenesisInterface;
use App\Domain\Account\Floor\FloorInterface;

interface ProfessionInterface
{
    public function getId(): int;
    public function getGenesis(): GenesisInterface;
    public function getIcon(): string;
    public function getName(FloorInterface $floor): string;
    public function getNameMale(): string;
    public function getNameFemale(): string;
}
