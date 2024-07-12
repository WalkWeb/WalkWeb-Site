<?php

declare(strict_types=1);

namespace App\Domain\Statistic;

interface StatisticInterface
{
    public function getTotalUser(): int;
}
