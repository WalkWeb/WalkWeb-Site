<?php

declare(strict_types=1);

namespace Test\src\Domain\Statistic;

use App\Domain\Statistic\Statistic;
use App\Domain\Statistic\StatisticRepository;
use Test\AbstractTest;
use WalkWeb\NW\AppException;

class StatisticTest extends AbstractTest
{
    /**
     * @throws AppException
     */
    public function testStatisticGetTotalUser(): void
    {
        $container = self::getContainer();
        $statistic = new Statistic(new StatisticRepository($container));

        self::assertEquals(11, $statistic->getTotalUser());
    }
}
