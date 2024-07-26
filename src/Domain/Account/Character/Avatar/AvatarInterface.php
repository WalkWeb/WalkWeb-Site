<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Avatar;

use App\Domain\Account\Character\Genesis\GenesisInterface;
use App\Domain\Account\Floor\FloorInterface;

interface AvatarInterface
{
    public function getId(): int;
    public function getGenesis(): GenesisInterface;
    public function getFloor(): FloorInterface;
    public function getOriginUrl(): string;
    public function getSmallUrl(): string;
}
