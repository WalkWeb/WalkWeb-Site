<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Genesis;

use App\Domain\Theme\ThemeInterface;

interface GenesisInterface
{
    // TODO min-max value

    public function getId(): int;
    public function getTheme(): ThemeInterface;
    public function getIcon(): string;
    public function getPlural(): string;
    public function getSingle(): string;
}
