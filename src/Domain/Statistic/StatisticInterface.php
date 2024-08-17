<?php

declare(strict_types=1);

namespace App\Domain\Statistic;

interface StatisticInterface
{
    public function getTotalUsers(): int;
    public function getTotalPosts(): int;
    public function getTotalComments(): int;
    public function getTotalTags(): int;
}
