<?php

declare(strict_types=1);

namespace App\Domain\Account\Character\Season;

interface SeasonInterface
{
    public const SEASON_1 = 1;

    public function getId(): int;
    public function getName(): string;
}
