<?php

declare(strict_types=1);

namespace App\Domain\Account\Carma;

use App\Domain\Account\Character\Season\Season;

interface CarmaInterface
{
    public function getId(): string;
    public function getAccountId(): string;
    public function getSeason(): Season;
    public function getCarma(): int;
    public function getUses(): int;
    public function getAvailable(): int;
}
